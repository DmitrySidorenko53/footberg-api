<?php

namespace Database\Seeders;

use App\Enums\EducationDegreeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationalInstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('educational_institutions')->insertOrIgnore($this->getEducationalInstitutes());
    }

    private function getEducationalInstitutes(): array
    {

        return [
            [
                'title' => 'Витебская ордена «Знак Почета» государственная академия ветеринарной медицины',
                'degree' => EducationDegreeEnum::HIGHER->name
            ],
            [
                'title' => 'Гродненский государственный аграрный университет',
                'degree' => EducationDegreeEnum::HIGHER->name
            ],
            [
                'title' => 'Аграрный колледж «ВГАВМ»',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Волковысский государственный аграрный колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Ильянский государственный аграрный колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Климовичский колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Ляховичский государственный аграрный колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Пинский государственный аграрный технологический колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Речицкий государственный аграрный колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'title' => 'Смиловичский государственный аграрный колледж',
                'degree' => EducationDegreeEnum::SECONDARY->name
            ],
        ];
    }
}
