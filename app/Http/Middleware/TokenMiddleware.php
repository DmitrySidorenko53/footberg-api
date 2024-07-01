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
        $tokenCandidate = $request->bearerToken();

        if (!$tokenCandidate) {
            return new ApiFailResponse([], 401, __('token.invalid_type'));
        }

        $tokenTemplateStart = StringGenerator::getSecurityTokenStart();

        $lengthOfTokenStart = strlen($tokenTemplateStart);

        $tokenCandidateStart = substr($tokenCandidate, 0, $lengthOfTokenStart);
        $tokenCandidateStart = trim($tokenCandidateStart);

        if ($tokenTemplateStart !== $tokenCandidateStart) {
            return new ApiFailResponse([], 401, __('token.invalid_format'));
        }

        $tokenCandidate = substr($tokenCandidate, $lengthOfTokenStart);

        //todo search if token in db encrypted
        /** @var SecurityToken $token */
        $token = SecurityToken::query()->where('token', $tokenCandidate)
            ->where('is_valid', true)
            ->where('is_deleted', false)
            ->with('user.roles')
            ->first();

        if (!$token) {
            return new ApiFailResponse([], 401, __('token.invalid'));
        }

        if ($token->valid_until < now()) {
            return new ApiFailResponse([], 401, __('token.expired'));
        }

        $candidate = $token->user;

        if (!$candidate) {
            return new ApiFailResponse([], 401, __('token.no_user'));
        }

        Auth::setUser($candidate);

        return $next($request);
    }
}
