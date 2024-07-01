<?php

namespace App\Http\Dto\Requests\Profile;

use App\Enums\EducationDegreeEnum;
use App\Enums\RoleEnum;
use App\Http\Dto\Requests\AbstractDto;
use App\Interfaces\DtoInterface;
use App\Models\EducationalInstitution;
use Illuminate\Validation\Rule;


class ProfileFillDto extends AbstractDto implements DtoInterface
{
    public string $surname;
    public string $name;
    public string $patronymic;
    public string $birthDate;
    public string $workplace;
    public string $position;
    public string $specialization;
    public array $roleIds;
    public array $educationIds;
    public array $educations;

    public function rules(): array
    {
        return [
            'surname' => 'string|between:2,100',
            'name' => 'string|between:2,100|required_with:surname',
            'patronymic' => 'nullable|string|between:2,100',
            'birthDate' => 'nullable|date',
            'workplace' => 'nullable|string|between:2,255',
            'specialization' => 'string|between:2,255|required_with:educations,educationIds',
            'position' => 'string|between:2,255|required_with:workplace',
            'roleIds' => 'required|array|max:2',
            'roleIds.*' => ['required', 'integer', Rule::in(RoleEnum::keys(RoleEnum::visibleRoles(), true))],
            'educationIds' => 'array|max:6',
            'educationIds.*' => 'required_array_keys:id,startDate,endDate',
            'educationIds.*.id' => 'exists:educational_institutions,id|integer',
            'educationIds.*.startDate' => 'date_format:Y/m/d',
            'educationIds.*.endDate' => 'date_format:Y/m/d|after:educationIds.*.startDate',
            'educations' => 'array|max:6',
            'educations.*' => 'required_array_keys:title,degree,startDate,endDate',
            'educations.*.title' => 'unique:educational_institutions,title|string|between:2,255',
            'educations.*.degree' => ['string', Rule::in(EducationDegreeEnum::keys())],
            'educations.*.startDate' => 'date_format:Y/m/d',
            'educations.*.endDate' => 'date_format:Y/m/d|after:educations.*.startDate',
        ];
    }

    public function messages(): array
    {
        return [
            'surname.string' => __('validation.string', ['attribute' => 'surname']),
            'surname.between' => __('validation.between', ['attribute' => 'surname', 'min' => 2, 'max' => 100]),

            'name.required_with' => __('validation.required_with', ['attribute' => 'name', 'other' => 'surname']),
            'name.string' => __('validation.string', ['attribute' => 'name']),
            'name.between' => __('validation.between', ['attribute' => 'name', 'min' => 2, 'max' => 100]),

            'patronymic.string' => __('validation.string', ['attribute' => 'patronymic']),
            'patronymic.between' => __('validation.between', ['attribute' => 'patronymic', 'min' => 2, 'max' => 100]),

            'birthDate.date' => __('validation.date', ['attribute' => 'birthDate']),

            'workplace.string' => __('validation.string', ['attribute' => 'workplace']),
            'workplace.between' => __('validation.between', ['attribute' => 'workplace', 'min' => 2, 'max' => 255]),

            'specialization.required_with' => __('validation.required_with', ['attribute' => 'specialization', 'other' => 'education|educationIds']),
            'specialization.string' => __('validation.string', ['attribute' => 'specialization']),
            'specialization.between' => __('validation.between', ['attribute' => 'specialization', 'min' => 2, 'max' => 255]),

            'position.required_with' => __('validation.required_with', ['attribute' => 'position', 'other' => 'workplace']),
            'position.string' => __('validation.string', ['attribute' => 'position']),
            'position.between' => __('validation.between', ['attribute' => 'position', 'min' => 2, 'max' => 255]),

            'roleIds.required' => __('validation.required', ['attribute' => 'roleIds']),
            'roleIds.array' => __('validation.array', ['attribute' => 'roleIds']),
            'roleIds.max' => __('validation.array_max', ['attribute' => 'roleIds', 'max' => 2]),

            'roleIds.*.required' => __('validation.required', ['attribute' => 'roleIds.id']),
            'roleIds.*.integer' => __('validation.integer', ['attribute' => 'roleIds.id']),
            'roleIds.*.in' => __('validation.in_array', ['array' => RoleEnum::visibleRoles(true)]),

            'educationIds.array' => __('validation.array', ['attribute' => 'educationIds']),
            'educationIds.max' => __('validation.array_max', ['attribute' => 'educationIds', 'max' => 6]),

            'educationIds.*.required_array_keys' => __('validation.required_array_keys', ['array' => 'educationIds', 'keys' => 'id, startDate, endDate']),

            'educationIds.*.id.integer' => __('validation.integer', ['attribute' => 'id']),
            'educationIds.*.id.exists' => __('validation.exists', ['attribute' => 'id', 'model' => EducationalInstitution::class]),
            'educationIds.*.startDate.date_format' => __('validation.date_format', ['attribute' => 'startDate', 'format' => 'year/month/day']),
            'educationIds.*.endDate.date_format' => __('validation.date_format', ['attribute' => 'endDate', 'format' => 'year/month/day']),
            'educationIds.*.endDate.after' => __('validation.after_start'),

            'educations.array' => __('validation.array', ['attribute' => 'educations']),
            'educations.max' => __('validation.array_max', ['attribute' => 'educations', 'max' => 6]),

            'educations.*.required_array_keys' => __('validation.required_array_keys', ['array' => 'educations', 'keys' => 'title, degree, startDate, endDate']),


            'educations.*.title.string' => __('validation.string', ['attribute' => 'title']),
            'educations.*.title.unique' => __('validation.unique', ['attribute' => 'title']),
            'educations.*.title.between' => __('validation.between', ['attribute' => 'title', 'min' => 2, 'max' => 255]),
            'educations.*.degree.string' => __('validation.string', ['attribute' => 'degree']),
            'educations.*.degree.in' => __('validation.in_array', ['array' => EducationDegreeEnum::keys(true, true)]),
            'educations.*.startDate.date_format' => __('validation.date_format', ['attribute' => 'startDate', 'format' => 'year/month/day']),
            'educations.*.endDate.date_format' => __('validation.date_format', ['attribute' => 'endDate', 'format' => 'year/month/day']),
            'educations.*.endDate.after' => __('validation.after_start'),
        ];
    }
}
