<?php

namespace App\Http\Middleware;

use App\Helpers\StringGenerator;
use App\Http\Responses\ApiFailResponse;
use App\Models\SecurityToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return new ApiFailResponse([], 401, 'There is no authorization header');
        }

        $authType = config('auth.api_auth_type');

        if (!str_starts_with($authHeader, $authType)) {
            return new ApiFailResponse([], 400, 'Invalid authorization type');
        }

        $tokenCandidate = substr($authHeader, strlen($authType) + 1);

        $tokenTemplateStart = StringGenerator::getSecurityTokenStart();

        $lengthOfTokenStart = strlen($tokenTemplateStart);

        $tokenCandidateStart = substr($tokenCandidate, 0, $lengthOfTokenStart);
        $tokenCandidateStart = trim($tokenCandidateStart);

        if ($tokenTemplateStart !== $tokenCandidateStart) {
            return new ApiFailResponse([], 400, 'Invalid token format');
        }

        $tokenCandidate = substr($tokenCandidate, $lengthOfTokenStart);

        //todo search if token in db encrypted
        /** @var SecurityToken $token */
        $token = SecurityToken::query()->where('token', $tokenCandidate)
            ->where('is_valid', true)
            ->where('is_deleted', false)
            ->with('user')
            ->first();

        if (!$token) {
            return new ApiFailResponse([], 400, 'Invalid token');
        }

        if ($token->valid_until < now()) {
            return new ApiFailResponse([], 400, 'Token is expired. Need to refresh token');
        }

        $candidate = $token->user;

        if ($candidate) {
            Auth::setUser($candidate);
        }

        return $next($request);
    }
}
