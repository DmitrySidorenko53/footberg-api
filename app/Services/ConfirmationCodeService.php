<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Exceptions\ServiceException;
use App\Exceptions\TooManyRequestsException;
use App\Helpers\Filters\BetweenFilter;
use App\Helpers\Filters\DefaultFilter;
use App\Http\Dto\Requests\Security\SecurityCodeDto;
use App\Http\Dto\Response\Security\ConfirmationCodeDto;
use App\Http\Dto\Response\Security\ResetPasswordCodeDto;
use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Models\ConfirmationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfirmationCodeService implements ConfirmationCodeServiceInterface
{
    private ConfirmationCodeRepositoryInterface $confirmationCodeRepository;

    public function __construct(ConfirmationCodeRepositoryInterface $confirmationCodeRepository)
    {
        $this->confirmationCodeRepository = $confirmationCodeRepository;
    }

    /**
     * @throws ServiceException
     * @throws InvalidIncomeTypeException
     */
    public function createConfirmationCode($user, $scope = 'confirm'): \App\Http\Dto\Response\AbstractDto
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $code = (string)rand(100000, 999999);

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->code_text = Hash::make($code);
        $confirmationCode->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $confirmationCode->valid_until = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
        $confirmationCode->user_id = $user->user_id;
        $confirmationCode->type = $scope;

        $isSuccess = $this->confirmationCodeRepository->save($confirmationCode);

        if (!$isSuccess) {
            throw new ServiceException(__('exceptions.error_while_creating', ['model' => ConfirmationCode::class]));
        }

        $dto = ($scope == 'confirm') ? ConfirmationCodeDto::class : ResetPasswordCodeDto::class;

        return (new $dto($confirmationCode))
            ->build([
                'code' => $code
            ]);
    }


    //todo code: 2 times in a day

    /**
     * @throws ServiceException|InvalidIncomeTypeException
     * @throws TooManyRequestsException
     */
    public function refreshCode($user, $scope = 'confirm')
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $countOfSendCodeForToday = $this->countSentCodesForToday($user, $scope);

        if ($countOfSendCodeForToday > 3) {
            throw new TooManyRequestsException(__('code.too_many_attempts'));
        }

        /** @var ConfirmationCode $lastValidCode */
        $lastValidCode = $user->getLastValidCode($scope);
        $whenAbleSendNewCode = Carbon::parse($lastValidCode->created_at)->addMinutes(10);

        if (now()->greaterThan($whenAbleSendNewCode)) {
            throw new TooManyRequestsException(__('code.repeat_later'));
        }

        $userCodesIds = $user->codes()->where('type', $scope)->pluck('code_id')->toArray();

        $this->confirmationCodeRepository->updateWhereIn('code_id', $userCodesIds, [
            'is_expired' => true
        ]);

        return $this->createConfirmationCode($user, $scope);
    }

    /**
     * @throws ServiceException
     * @throws InvalidIncomeTypeException
     */
    public function tryConfirmCode($code, $dto): void
    {
        if (!$dto instanceof SecurityCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityCodeDto::class);
        }

        if (!$this->isValid($code)) {
            throw new NotFoundHttpException(__('code.not_found'));
        }

        if (!Hash::check($dto->code, $code->code_text)) {
            $code->is_expired = true;
            $this->confirmationCodeRepository->save($code);
            throw new InvalidArgumentException(__('code.invalid'));
        }

        $isConfirmed = $this->confirmCode($code);

        if (!$isConfirmed) {
            throw new ServiceException(__('code.confirm_error'));
        }
    }

    private function isValid($code): bool
    {
        if (!$code) {
            return false;
        }
        if (!$code instanceof ConfirmationCode) {
            return false;
        }
        if ($code->is_expired) {
            return false;
        }

        $validUntil = $code->valid_until;
        $now = Carbon::createFromFormat('Y-m-d H:i:s', now());

        if ($now->greaterThan($validUntil)) {
            return false;
        }

        return true;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    private function confirmCode($code)
    {
        if ($code && (!$code instanceof ConfirmationCode)) {
            throw new InvalidIncomeTypeException(__METHOD__, ConfirmationCode::class);
        }
        return $this->confirmationCodeRepository->update($code,
            [
                'confirmed_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'is_confirmed' => true,
                'is_expired' => true
            ]
        );
    }

    private function countSentCodesForToday($user, $scope): int
    {
        $filters = [
            new DefaultFilter('user_id', $user->user_id),
            new BetweenFilter('created_at', $this->getCurrentDayStartAndEnd()),
            new DefaultFilter('type', $scope)
        ];

        return $this->confirmationCodeRepository->countWithFilters($filters);
    }

    private function getCurrentDayStartAndEnd(string $format = 'Y-m-d H:i:s'): array
    {
        return [
            Carbon::now()->startOfDay()->format($format),
            Carbon::now()->endOfDay()->format($format)
        ];
    }
}
