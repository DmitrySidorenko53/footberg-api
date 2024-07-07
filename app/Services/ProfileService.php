<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Profile\ProfileShowDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\AccountDetailsRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\EducationServiceInterface;
use App\Interfaces\Service\ProfileServiceInterface;
use App\Interfaces\Service\RoleServiceInterface;
use App\Models\AccountDetails;
use Illuminate\Support\Facades\DB;

class ProfileService implements ProfileServiceInterface
{
    private AccountDetailsRepositoryInterface $accountDetailsRepository;
    private UserRepositoryInterface $userRepository;
    private RoleServiceInterface $roleService;
    private EducationServiceInterface $educationService;

    public function __construct(
        AccountDetailsRepositoryInterface $accountDetailsRepository,
        RoleServiceInterface              $roleService,
        EducationServiceInterface         $educationService,
        UserRepositoryInterface           $userRepository,
    )
    {
        $this->accountDetailsRepository = $accountDetailsRepository;
        $this->roleService = $roleService;
        $this->educationService = $educationService;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function fillDetails(DtoInterface $dto, $user)
    {
        if (!$dto instanceof ProfileFillDto) {
            throw new InvalidIncomeTypeException(__METHOD__, ProfileFillDto::class);
        }

        $details = new AccountDetails();
        $details->user_id = $user->user_id;
        $details->surname = $dto->surname ?? '';
        $details->name = $dto->name ?? '';
        $details->patronymic = $dto->patronymic ?? '';
        $details->work_place = $dto->workplace ?? '';
        $details->position = $dto->position ?? '';
        $details->specialization = $dto->specialization ?? '';
        $details->birth_date = $dto->birthDate ?? '';

        DB::transaction(function () use ($user, $dto, $details) {

            $this->accountDetailsRepository->updateOrInsert(
                [
                    'user_id' => $user->user_id
                ],
                $details->attributesToArray()
            );

            $roleIds = $dto->roleIds ?? [];
            $this->roleService->refreshUsersRoles($user, $roleIds);

            $educationIds = $dto->educationIds ?? [];
            $educations = $dto->educations ?? [];
            $this->educationService->refreshEducationsForUser($user, $educationIds, $educations);
        });

        return $this->getDetailsByUserId($user->user_id, true);
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function getDetailsByUserId($userId, $isMy): AbstractDto
    {
        $user = $this->userRepository->findById($userId, [
            'roles',
            'educations.degree',
            'details'
        ]);

        return (new ProfileShowDto($user))->build([
            'is_my' => $isMy
        ]);
    }
}
