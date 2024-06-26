<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Exceptions\ServiceException;
use App\Interfaces\Repository\ConfirmationCodeRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Models\ConfirmationCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

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
    public function createConfirmationCode($user): array
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

        $isSuccess = $this->confirmationCodeRepository->save($confirmationCode);

        if (!$isSuccess) {
            throw new ServiceException(__('exceptions.error_while_creating', ['model' => ConfirmationCode::class]));
        }

        return [
            'code' => $code,
            'created_at' => $confirmationCode->created_at,
            'valid_until' => Carbon::parse($confirmationCode->valid_until)->format('H:i:s d.m.Y')
        ];
    }


    /**
     * @throws ServiceException|InvalidIncomeTypeException
     */
    public function refreshCode($user)
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.already_active'));
        }

        $userCodesIds = $user->codes()->pluck('code_id')->toArray();

        $this->confirmationCodeRepository->updateWhereIn('code_id', $userCodesIds, [
            'is_expired' => true
        ]);

        return $this->createConfirmationCode($user);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function confirmCode($code)
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

    public function isValid($code, $codeToCompare): bool
    {
        if (!$code) {
            return false;
        }
        if (!$code instanceof ConfirmationCode) {
            return false;
        }

        $validUntil = $code->valid_until;
        $now = Carbon::createFromFormat('Y-m-d H:i:s', now());

        if ($now->greaterThan($validUntil)) {
            return false;
        }

        return true;
    }
}
