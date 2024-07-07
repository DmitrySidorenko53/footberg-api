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
                'id' => 1,
                'title' => 'Витебская ордена «Знак Почета» государственная академия ветеринарной медицины',
                'degree_id' => EducationDegreeEnum::HIGHER->name
            ],
            [
                'id' => 2,
                'title' => 'Гродненский государственный аграрный университет',
                'degree_id' => EducationDegreeEnum::HIGHER->name
            ],
            [
                'id' => 3,
                'title' => 'Аграрный колледж «ВГАВМ»',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 4,
                'title' => 'Волковысский государственный аграрный колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 5,
                'title' => 'Ильянский государственный аграрный колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 6,
                'title' => 'Климовичский колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 7,
                'title' => 'Ляховичский государственный аграрный колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 8,
                'title' => 'Пинский государственный аграрный технологический колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 9,
                'title' => 'Речицкий государственный аграрный колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
            [
                'id' => 10,
                'title' => 'Смиловичский государственный аграрный колледж',
                'degree_id' => EducationDegreeEnum::SECONDARY->name
            ],
        ];
    }
}
