<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\AccountDetailsRepositoryInterface;
use App\Interfaces\Repository\RoleUserRepositoryInterface;
use App\Interfaces\Repository\UserEducationRepositoryInterface;
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
    )
    {
        $this->accountDetailsRepository = $accountDetailsRepository;
        $this->roleService = $roleService;
        $this->educationService = $educationService;
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

            $this->roleService->refreshUsersRoles($user, $dto->roleIds);

            $educationIds = $dto->educationIds ?? [];
            $educations = $dto->educations ?? [];

            $this->educationService->refreshEducationsForUser($user, $educationIds, $educations);
        });

        return $dto;
    }

    public function getDetailsByUserId($authId, $searchId)
    {
        if ($searchId == null || $searchId == $authId) {
            $isMy = true;
            $userId = $authId;
        }

        else {
            $isMy = false;
            $userId = $searchId;
        }

        $details = $this->userRepository->findById($userId, [
            'details',
            'educations.degree',
            'roles',
        ]);

        //todo return new DetailsDto;
    }
}
