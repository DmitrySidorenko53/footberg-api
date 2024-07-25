<?php

namespace App\Services;

use App\Enums\ConfirmationCodeScopeEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\TwoFA\CheckPhoneNumberDto;
use App\Http\Dto\Response\Security\CodeDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SmsServiceInterface;
use App\Models\User;
use App\Notifications\CheckPhoneNotification;
use App\Traits\PhoneNumberTrait;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SmsService implements SmsServiceInterface
{
    use PhoneNumberTrait;

    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private UserRepositoryInterface $userRepository;

    /**
     * @param ConfirmationCodeServiceInterface $confirmationCodeService
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(ConfirmationCodeServiceInterface $confirmationCodeService, UserRepositoryInterface $userRepository)
    {
        $this->confirmationCodeService = $confirmationCodeService;
        $this->userRepository = $userRepository;
    }


    public function sendSmsCode($user, string $newPhoneNumber = null): CodeDto
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        if (!$user->security_phone_number && !$newPhoneNumber) {
            throw new InvalidArgumentException(__('two_factor.no_phone_number'));
        }

        $verification = DB::transaction(function () use ($newPhoneNumber, $user) {

            if (!$user->security_phone_number) {
                $user->security_phone_number = $newPhoneNumber;
                $this->userRepository->save($user);
            }

            return $this->confirmationCodeService->refreshCode($user, ConfirmationCodeScopeEnum::PHONE);
        });

        //$code = $verification->phone_confirmation->code;
        //$user->notify(new CheckPhoneNotification($code));

        return $verification;
    }

    public function addPhoneNumberFor2Fa(DtoInterface $dto, $user): CodeDto
    {
        if (!$dto instanceof CheckPhoneNumberDto) {
            throw new InvalidIncomeTypeException(__METHOD__, CheckPhoneNumberDto::class);
        }

        $phoneNumber = $this->preparePhoneNumber($dto->countryCode, $dto->number, true);

        return $this->sendSmsCode($user, $phoneNumber);
    }
}
