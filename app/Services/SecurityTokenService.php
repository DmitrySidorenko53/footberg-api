<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Exceptions\TooManyRequestsException;
use App\Helpers\StringGenerator;
use App\Http\Dto\Requests\Security\SecurityRefreshTokenDto;
use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Security\GeneratedTokenDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\SecurityTokenRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Models\SecurityToken;
use App\Models\User;
use App\Traits\CurrentDayTrait;

class SecurityTokenService implements SecurityTokenServiceInterface
{
    use CurrentDayTrait;

    private SecurityTokenRepositoryInterface $securityTokenRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(SecurityTokenRepositoryInterface $securityTokenRepository, UserRepositoryInterface $userRepository)
    {
        $this->securityTokenRepository = $securityTokenRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function generateToken($user): AbstractDto
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $this->resetTokens($user);

        //todo save encrypted token
        $token = new SecurityToken();
        $token->token = StringGenerator::generateSecurityToken(255);
        $token->created_at = now()->format('Y-m-d H:i:s');
        $token->valid_until = now()->addDays(3)->format('Y-m-d H:i:s');
        $token->is_valid = true;
        $token->user_id = $user->user_id;

        $this->securityTokenRepository->save($token);

        return GeneratedTokenDto::create($token);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function refresh(DtoInterface $dto): AbstractDto
    {
        if (!$dto instanceof SecurityRefreshTokenDto) {
            throw new InvalidIncomeTypeException(__METHOD__, SecurityRefreshTokenDto::class);
        }

        $user = $this->userRepository->findById($dto->userId);

        return $this->generateToken($user);
    }

    /**
     * @param $user
     * @return void
     */
    public function resetTokens($user): void
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $tokens = $user->tokens()->pluck('token')->toArray();

        if (!$tokens) {
            return;
        }

        $this->securityTokenRepository->updateWhereIn('token', $tokens, [
            'is_valid' => false,
        ]);
    }
}
