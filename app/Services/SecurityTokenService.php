<?php

namespace App\Services;

use App\Helpers\GenerateString;
use App\Interfaces\Repository\SecurityTokenRepositoryInterface;
use App\Interfaces\Service\SecurityTokenServiceInterface;
use App\Models\SecurityToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class SecurityTokenService implements SecurityTokenServiceInterface
{
    private SecurityTokenRepositoryInterface $securityTokenRepository;

    public function __construct(SecurityTokenRepositoryInterface $securityTokenRepository)
    {
        $this->securityTokenRepository = $securityTokenRepository;
    }

    public function generateToken($user)
    {
        if ($user && (!$user instanceof User)) {
            throw new InvalidArgumentException(get_class($this) . " generate token method must receive a valid user model");
        }

        $this->resetUserTokens($user);

        $key = GenerateString::generateSecurityToken(255);

        $token = new SecurityToken();
        $token->token = Hash::make($key);
        $token->created_at = Carbon::now(3)->format('Y-m-d H:i:s');
        $token->valid_until = Carbon::now(3)->addHours(8)->format('Y-m-d H:i:s');
        $token->is_valid = true;
        $token->user_id = $user->user_id;

        $this->securityTokenRepository->save($token);

        return [
            'token' => $key,
            'created_at' => $token->created_at,
            'valid_until' => $token->valid_until,
        ];
    }

    /**
     * @param User $user
     * @return void
     */
    public function resetUserTokens(User $user): void
    {
        $tokens = $user->tokens()->pluck('token')->toArray();

        if (!$tokens) {
            return;
        }

        $this->securityTokenRepository->updateWhereIn('token', $tokens, [
            'is_valid' => false,
            'is_deleted' => true,
            'deleted_at' => Carbon::now(3)->format('Y-m-d H:i:s')
        ]);
    }
}
