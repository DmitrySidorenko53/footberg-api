<?php

namespace App\Services;


use App\Enums\EmailScopeEnum;
use App\Enums\RoleEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Account\AccountLoginDto;
use App\Http\Dto\Requests\Account\AccountRegisterDto;
use App\Http\Dto\Requests\Code\CodeDto;
use App\Http\Dto\Requests\Code\RefreshCodeDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Interfaces\Service\SmsServiceInterface;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityService implements SecurityServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private RoleUserRepositoryInterface $roleUserRepository;
    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private SecurityTokenServiceInterface $securityTokenService;
    private SmsServiceInterface $smsService;

    public function __construct(
        UserRepositoryInterface          $userRepository,
        ConfirmationCodeServiceInterface $confirmationCodeService,
        SecurityTokenServiceInterface    $securityTokenService,
        RoleUserRepositoryInterface      $roleUserRepository,
        SmsServiceInterface              $smsService)
    {
        $this->userRepository = $userRepository;
        $this->confirmationCodeService = $confirmationCodeService;
        $this->securityTokenService = $securityTokenService;
        $this->roleUserRepository = $roleUserRepository;
        $this->smsService = $smsService;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function register(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof AccountRegisterDto) {
            throw new InvalidIncomeTypeException(__METHOD__, AccountRegisterDto::class);
        }
        $user = new User();
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->register_at = now()->format('Y-m-d H:i:s');

        $code = DB::transaction(function () use ($user) {
            $this->userRepository->save($user);
            return $this->confirmationCodeService->createConfirmationCode($user);
        });

        $email = EmailContentHelper::build($code, EmailScopeEnum::CONFIRMATION);
        dispatch(new SendEmailJob($email));

        return $code;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function login(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof AccountLoginDto) {
            throw new InvalidIncomeTypeException(__METHOD__, AccountLoginDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email, 'tokens')->first();

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        //todo not more than 10 attempts by day

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException(__('exceptions.incorrect_password'));
        }

        if ($user->two_fa_enabled) {
            Session::put('candidate', $user);
            return $this->smsService->sendSmsCode($user);
        }

        $user->last_login_at = now()->format('Y-m-d H:i:s');
        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function refreshCode(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof RefreshCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, RefreshCodeDto::class);
        }

        $user = $this->userRepository->findById($dto->userId, 'codes');

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.already_active'));
        }

        $code = $this->confirmationCodeService->refreshCode($user);

        $email = EmailContentHelper::build($code, EmailScopeEnum::CONFIRMATION);
        dispatch(new SendEmailJob($email));

        return $code;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function confirmAccount(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof CodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, CodeDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.already_active'));
        }

        $code = $user->getLastValidCode();

        $codeCandidate = $dto->code;

        DB::transaction(function () use ($user, $codeCandidate, $code) {
            $this->confirmationCodeService->tryConfirmCode($code, $codeCandidate);

            $user->is_active = true;
            $user->last_login_at = now()->format('Y-m-d H:i:s');

            $this->userRepository->save($user);

            $this->roleUserRepository->insert([
                'role_id' => RoleEnum::VISITOR->value,
                'user_id' => $user->user_id
            ]);
        });

        return $this->securityTokenService->generateToken($user);
    }
}
