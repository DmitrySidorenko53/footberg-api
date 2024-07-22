<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\Security\SecurityCheckPhoneNumberDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SmsServiceInterface;
use App\Models\User;
use App\Traits\PhoneNumberTrait;
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


    public function sendCodeForTwoFactor(DtoInterface $dto, $user): AbstractDto
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        if (!$dto instanceof SecurityCheckPhoneNumberDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityCheckPhoneNumberDto::class);
        }

        if ($user->enabled_two_step_verification) {
            throw new InvalidArgumentException('two_factor.already_enabled');
        }

        $phoneNumber = $this->preparePhoneNumber($dto->countryCode, $dto->number, true);

        $user->security_phone_number = $phoneNumber;
        $this->userRepository->save($user);

        //send code

        return $this->confirmationCodeService->createConfirmationCode($user, 'phone');
    }
}
