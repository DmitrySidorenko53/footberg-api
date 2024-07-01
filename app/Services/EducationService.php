<?php

namespace App\Services;

use App\Exceptions\InvalidIncomeTypeException;
use App\Interfaces\Repository\EducationalInstitutionRepositoryInterface;
use App\Interfaces\Repository\UserEducationRepositoryInterface;
use App\Interfaces\Service\EducationServiceInterface;
use Carbon\Carbon;
use InvalidArgumentException;

class EducationService implements EducationServiceInterface
{
    private EducationalInstitutionRepositoryInterface $educationalInstitutionRepository;
    private UserEducationRepositoryInterface $userEducationRepository;

    /**
     * @param EducationalInstitutionRepositoryInterface $educationalInstitutionRepository
     * @param UserEducationRepositoryInterface $userEducationRepository
     */
    public function __construct(
        EducationalInstitutionRepositoryInterface $educationalInstitutionRepository,
        UserEducationRepositoryInterface          $userEducationRepository
    )
    {
        $this->educationalInstitutionRepository = $educationalInstitutionRepository;
        $this->userEducationRepository = $userEducationRepository;
    }


    public function refreshEducationsForUser($user, array $educationIds, array $educations): void
    {
        if (!$educationIds || !$educations) {
            return;
        }

        $this->cleanEducations($user);

        $this->addEducationsForUsers($user, $educationIds, $educations, true);
    }

    public function addEducationsForUsers($user, array $educationIds, array $educations, $forRefresh = false): void
    {
        if (!$forRefresh) {
            $currentEducationsCount = $this->userEducationRepository->countBy('user_id', $user->user_id);
            $wantToAddEducationsCount = sizeof($educations) + sizeof($educationIds);

            if ($currentEducationsCount + $wantToAddEducationsCount > 6) {
                throw new InvalidArgumentException(__('exceptions.too_many_educations'));
            }
        }

        $this->addExistingEducationalInstitutionsToUser($user, $educationIds);
        $this->addNewEducationInstitutionsToUser($user, $educations);
    }

    private function addExistingEducationalInstitutionsToUser($user, array $educationIds): void
    {
        $data = [];
        foreach ($educationIds as $educationId) {
            $data[] = [
                'user_id' => $user->user_id,
                'education_id' => $educationId['id'],
                'start_date' => Carbon::createFromFormat('Y/m/d', $educationId['startDate']),
                'end_date' => Carbon::createFromFormat('Y/m/d', $educationId['endDate']),
            ];
        }
        $this->userEducationRepository->insertIgnore($data);
    }

    private function addNewEducationInstitutionsToUser($user, array $educations): void
    {
        $data = [];
        foreach ($educations as $education) {
            $newEducationInstitutionsId = $this->educationalInstitutionRepository->insertGetId([
                'title' => $education['title'],
                'degree' => $education['degree']
            ]);
            $data[] = [
                'user_id' => $user->user_id,
                'education_id' => $newEducationInstitutionsId,
                'start_date' => Carbon::createFromFormat('Y/m/d', $education['startDate']),
                'end_date' => Carbon::createFromFormat('Y/m/d', $education['endDate']),
            ];
        }
        $this->userEducationRepository->insertIgnore($data);
    }

    private function cleanEducations($user): void
    {
        $this->userEducationRepository->deleteBy('user_id', $user->user_id);
    }
}
