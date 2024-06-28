<?php

namespace Database\Seeders;

use App\Enums\EducationDegreeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationalDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('educational_degrees')->insertOrIgnore($this->mapInsertData());
    }

    private function mapInsertData(): array
    {
        $cases = EducationDegreeEnum::cases();
        $degrees = [];
        foreach ($cases as $case) {
            $degrees[] = [
                'degree' => strtolower($case->name),
                'description' => $case->value
            ];
        }
        return $degrees;
    }
}
