<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Http\Dto\Requests\Profile\ProfileFillDto;
use App\Http\Dto\Response\AbstractDto;
use App\Http\Dto\Response\Profile\ProfileShowDto;
use App\Interfaces\DtoInterface;
use App\Interfaces\Repository\AccountDetailsRepositoryInterface;
use App\Interfaces\Repository\LocaleRepositoryInterface;
use App\Interfaces\Repository\UserRepositoryInterface;
use App\Interfaces\Service\EducationServiceInterface;
use App\Interfaces\Service\ProfileServiceInterface;
use App\Interfaces\Service\RoleServiceInterface;
use App\Models\AccountDetails;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ProfileService implements ProfileServiceInterface
{
    private AccountDetailsRepositoryInterface $accountDetailsRepository;
    private UserRepositoryInterface $userRepository;
    private RoleServiceInterface $roleService;
    private EducationServiceInterface $educationService;
    private LocaleRepositoryInterface $localeRepository;

    public function __construct(
        AccountDetailsRepositoryInterface $accountDetailsRepository,
        RoleServiceInterface              $roleService,
        EducationServiceInterface         $educationService,
        UserRepositoryInterface           $userRepository,
        LocaleRepositoryInterface         $localeRepository,
    )
    {
        $this->accountDetailsRepository = $accountDetailsRepository;
        $this->roleService = $roleService;
        $this->educationService = $educationService;
        $this->userRepository = $userRepository;
        $this->localeRepository = $localeRepository;
    }

    /**
     * @throws InvalidIncomeTypeException
     */
    public function fillDetails(DtoInterface $dto, $user): AbstractDto
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

        return ProfileShowDto::create($user, ['is_my' => $isMy]);
    }

    public function changeLanguage($user, $wantedLanguage): void
    {
        if (!$user instanceof User) {
            throw new InvalidIncomeTypeException(__METHOD__, User::class);
        }

        $supportedLocales = $this->localeRepository->pluck('locale');

        if (!in_array($wantedLanguage, $supportedLocales)) {
            throw new InvalidArgumentException(__('exceptions.unsupported_locale'));
        }

        $this->userRepository->update($user, [
            'locale' => $wantedLanguage
        ]);

        app()->setLocale($wantedLanguage);
    }
}
