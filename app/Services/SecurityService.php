<?php

namespace App\Services;


use App\Enums\EmailScopeEnum;
use App\Enums\RoleEnum;
use App\Exceptions\InvalidIncomeTypeException;
use App\Exceptions\ServiceException;
use App\Helpers\EmailContentHelper;
use App\Http\Dto\Requests\Security\SecurityCodeDto;
use App\Http\Dto\Requests\Security\SecurityLoginDto;
use App\Http\Dto\Requests\Security\SecurityRefreshCodeDto;
use App\Http\Dto\Requests\Security\SecurityRegisterDto;
use App\Http\Dto\Response\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\ConfirmationCodeServiceInterface;
use App\Interfaces\Service\SecurityServiceInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Jobs\SendEmailJob;
use App\Models\RoleUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecurityService implements SecurityServiceInterface
{
    private UserRepositoryInterface $userRepository;
    private RoleUserRepositoryInterface $roleUserRepository;
    private ConfirmationCodeServiceInterface $confirmationCodeService;
    private SecurityTokenServiceInterface $securityTokenService;

    public function __construct(
        UserRepositoryInterface          $userRepository,
        ConfirmationCodeServiceInterface $confirmationCodeService,
        SecurityTokenServiceInterface    $securityTokenService,
        RoleUserRepositoryInterface      $roleUserRepository)
    {
        $this->userRepository = $userRepository;
        $this->confirmationCodeService = $confirmationCodeService;
        $this->securityTokenService = $securityTokenService;
        $this->roleUserRepository = $roleUserRepository;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function register(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof SecurityRegisterDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityRegisterDto::class);
        }
        $user = new User();
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password, ['rounds' => 12]);
        $user->register_at = Carbon::now()->format('Y-m-d H:i:s');

        $code = DB::transaction(function () use ($user) {
            $isSuccess = $this->userRepository->save($user);

            if (!$isSuccess) {
                throw new ServiceException(__('exceptions.error_while_creating', ['model' => User::class]));
            }
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
        if (!$dto instanceof SecurityLoginDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityLoginDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findBy('email', $dto->email, 'tokens')->first();

        if (!$user->isActiveOrNotDeleted()) {
            throw new NotFoundHttpException(__('exceptions.inactive'));
        }

        if (!Hash::check($dto->password, $user->password)) {
            throw new InvalidArgumentException(__('exceptions.incorrect_password'));
        }

        $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');
        $this->userRepository->save($user);

        return $this->securityTokenService->generateToken($user);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function refreshCode(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof SecurityRefreshCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityRefreshCodeDto::class);
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
        if (!$dto instanceof SecurityCodeDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityCodeDto::class);
        }

        /** @var User $user */
        $user = $this->userRepository->findById($dto->userId, 'codes');

        if ($user->is_active) {
            throw new InvalidArgumentException(__('exceptions.already_active'));
        }

        $code = $user->getLastValidCode();

        DB::transaction(function () use ($user, $dto, $code) {
            $this->confirmationCodeService->tryConfirmCode($code, $dto);

            $user->is_active = true;
            $user->last_login_at = Carbon::now()->format('Y-m-d H:i:s');

            $this->userRepository->save($user);

            $roleUser = new RoleUser();
            $roleUser->user_id = $user->user_id;
            $roleUser->role_id = RoleEnum::VISITOR->value;
            $this->roleUserRepository->save($roleUser);
        });

        return $this->securityTokenService->generateToken($user);
    }
}
