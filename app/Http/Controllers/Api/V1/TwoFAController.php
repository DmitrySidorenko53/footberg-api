<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Dto\Requests\TwoFA\CheckPhoneNumberDto;
use App\Http\Dto\Requests\TwoFA\TwoFactorCodeDto;
use App\Http\Responses\ApiResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Interfaces\Service\SmsServiceInterface;
use App\Interfaces\Service\TwoFactorServiceInterface;
use Illuminate\Support\Facades\Auth;

class TwoFAController extends Controller
{
    private TwoFactorServiceInterface $twoFactorService;
    private SmsServiceInterface $smsService;

    /**
     * @param TwoFactorServiceInterface $twoFactorService
     * @param SmsServiceInterface $smsService
     */
    public function __construct(
        TwoFactorServiceInterface     $twoFactorService,
        SmsServiceInterface $smsService,
    )
    {
        $this->twoFactorService = $twoFactorService;
        $this->smsService = $smsService;
    }

    public function confirmSentCode(TwoFactorCodeDto $dto): ApiResponse
    {
        //todo implement method confirmSentCode()
    }

    public function loginIf2FAEnabled(TwoFactorCodeDto $dto): ApiResponse
    {
        $data = $this->twoFactorService->loginWithSmsCode($dto);
        return new ApiSuccessResponse($data, 200, __('security.login'));
    }

    public function addPhoneNumber(CheckPhoneNumberDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->smsService->addPhoneNumberFor2Fa($dto, $user);
        return new ApiSuccessResponse($data, 200, $data->getMessage());
    }

    public function enableTwoFactorAuthentication(TwoFactorCodeDto $dto): ApiResponse
    {
        $user = Auth::user();
        $data = $this->twoFactorService->enableTwoFactorAuthentication($dto, $user);
        return new ApiSuccessResponse($data, 200, __('two_factor.enabled'));
    }

    public function disableTwoFactorAuthentication(): ApiResponse
    {
        $user = Auth::user();
        $data = $this->twoFactorService->disableTwoFactorAuthentication($user);
        return new ApiSuccessResponse($data, 200, __('two_factor.disabled'));
    }
}
