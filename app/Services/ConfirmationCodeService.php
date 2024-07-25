<?php

namespace App\Services;

use App\Enums\ConfirmationCodeScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Exceptions\ServiceException;
use App\Exceptions\TooManyRequestsException;
use App\Helpers\Filters\BetweenFilter;
use App\Helpers\Filters\DefaultFilter;
use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Security\EmailConfirmationCodeDto;
use App\Http\Dto\Response\Security\PhoneNumberCodeDto;
use App\Http\Dto\Response\Security\ResetPasswordCodeDto;
use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Models\ConfirmationCode;
use App\Models\User;
use App\Traits\CurrentDayTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConfirmationCodeService implements ConfirmationCodeServiceInterface
{
    use CurrentDayTrait;

    private ConfirmationCodeRepositoryInterface $confirmationCodeRepository;

    public function __construct(ConfirmationCodeRepositoryInterface $confirmationCodeRepository)
    {
        $this->confirmationCodeRepository = $confirmationCodeRepository;
    }

    /**
     * @throws ServiceException
     * @throws InvalidIncomeTypeException
     */
    public function createConfirmationCode($user, $scope = ConfirmationCodeScopeEnum::EMAIL): AbstractDto
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $code = (string)rand(100000, 999999);

        $confirmationCode = new ConfirmationCode();
        $confirmationCode->code_text = Hash::make($code);
        $confirmationCode->created_at = now()->format('Y-m-d H:i:s');
        $confirmationCode->valid_until = now()->addMinutes(5)->format('Y-m-d H:i:s');
        $confirmationCode->user_id = $user->user_id;
        $confirmationCode->type = $scope->value;

        $isSuccess = $this->confirmationCodeRepository->save($confirmationCode);

        if (!$isSuccess) {
            throw new ServiceException(__('exceptions.error_while_creating', ['model' => ConfirmationCode::class]));
        }

        $dtoTypes = [
            'email' => EmailConfirmationCodeDto::class,
            'reset' => ResetPasswordCodeDto::class,
            'phone' => PhoneNumberCodeDto::class,
        ];


        $dto = $dtoTypes[$scope->value];

        return $dto::create($confirmationCode, ['code' => $code]);
    }


    /**
     * @throws ServiceException|InvalidIncomeTypeException
     * @throws TooManyRequestsException
     */
    public function refreshCode($user, $scope = ConfirmationCodeScopeEnum::EMAIL): AbstractDto
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $this->checkPossibilityOfSending($user, $scope);

        $userCodesIds = $user->codes()->where('type', $scope->value)->pluck('code_id')->toArray();

        $this->confirmationCodeRepository->updateWhereIn('code_id', $userCodesIds, [
            'is_expired' => true
        ]);

        return $this->createConfirmationCode($user, $scope);
    }

    /**
     * @throws ServiceException
     * @throws InvalidIncomeTypeException
     */
    public function tryConfirmCode($code, $codeCandidate): void
    {
        if (!$this->isValid($code)) {
            throw new NotFoundHttpException(__('code.not_found'));
        }

        if (!Hash::check($codeCandidate, $code->code_text)) {
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
                'confirmed_at' => now()->format('Y-m-d H:i:s'),
                'is_confirmed' => true,
                'is_expired' => true
            ]
        );
    }

    /**
     * @param $user
     * @param $scope
     * @return void
     * @throws TooManyRequestsException
     */
    private function checkPossibilityOfSending($user, $scope): void
    {
        $filters = [
            new DefaultFilter('user_id', $user->user_id),
            new BetweenFilter('created_at', $this->getDayStartAndEnd(now())),
            new DefaultFilter('type', $scope->value)
        ];

        $countOfSentCodeForToday = $this->confirmationCodeRepository->countWithFilters($filters);

        if ($countOfSentCodeForToday == 0) {
            return;
        }

        if ($scope != ConfirmationCodeScopeEnum::PHONE && $countOfSentCodeForToday >= 3) {
            throw new TooManyRequestsException(__('code.too_many_attempts'));
        }

        /** @var ConfirmationCode $lastValidCode */
        $lastValidCode = $user->getLastValidCode($scope);

        if (!$lastValidCode) {
            return;
        }

        $whenAbleSendNewCode = Carbon::parse($lastValidCode->created_at)->addMinutes(2);

        if ($whenAbleSendNewCode->greaterThan(now())) {
            throw new TooManyRequestsException(__('code.repeat_later'));
        }
    }
}
