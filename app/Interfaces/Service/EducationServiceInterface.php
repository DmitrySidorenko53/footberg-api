<?php

namespace App\Interfaces\Service;

interface EducationServiceInterface
{
    public function refreshEducationsForUser($user, array $educationIds, array $educations);
    public function addEducationsForUsers($user, array $educationIds, array $educations, $forRefresh = false);
}
