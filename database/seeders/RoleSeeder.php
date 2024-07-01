<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore($this->mapInsertData());
    }

    private function mapInsertData(): array
    {
        $cases = RoleEnum::cases();
        $roles = [];
        foreach ($cases as $key => $case) {
            $roles[] = [
                'role_id' => $case->value,
                'shortcut' => $case->name,
                'description' => $this->descriptions()[$key]
            ];
        }
        return $roles;
    }

    private function descriptions(): array
    {
        return [
            'Закупщик',
            'Дилер',
            'Интерн',
            'Медсестра/медбрат',
            'Врач-хирург',
            'Ветеринар врач-хирург',
            'Студент',
            'Посититель',
            'Администратор'
        ];
    }
}
