<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
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
use Carbon\Carbon;

class SecurityTokenService implements SecurityTokenServiceInterface
{
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

        $this->resetUserTokens($user);

        //todo save encrypted token
        $token = new SecurityToken();
        $token->token = StringGenerator::generateSecurityToken(255);
        $token->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $token->valid_until = Carbon::now()->addHours(8)->format('Y-m-d H:i:s');
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
     * @param User $user
     * @return void
     */
    private function resetUserTokens(User $user): void
    {
        $tokens = $user->tokens()->pluck('token')->toArray();

        if (!$tokens) {
            return;
        }

        $this->securityTokenRepository->updateWhereIn('token', $tokens, [
            'is_valid' => false,
            'is_deleted' => true,
            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }


}
