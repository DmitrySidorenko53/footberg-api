<?php

namespace Database\Seeders;

use App\Enums\RoleNameEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore(
            [
                [
                    'role_id' => RoleNameEnum::BUYER->value,
                    'role_name' => RoleNameEnum::BUYER->name
                ],
                [
                    'role_id' => RoleNameEnum::DEALER->value,
                    'role_name' => RoleNameEnum::DEALER->name
                ],
                [
                    'role_id' => RoleNameEnum::INTERN->value,
                    'role_name' => RoleNameEnum::INTERN->name
                ],
                [
                    'role_id' => RoleNameEnum::NURSE->value,
                    'role_name' => RoleNameEnum::NURSE->name
                ],
                [
                    'role_id' => RoleNameEnum::SURGEON->value,
                    'role_name' => RoleNameEnum::SURGEON->name
                ],
                [
                    'role_id' => RoleNameEnum::VET_SURGEON->value,
                    'role_name' => RoleNameEnum::VET_SURGEON->name
                ],
                [
                    'role_id' => RoleNameEnum::STUDENT->value,
                    'role_name' => RoleNameEnum::STUDENT->name
                ],
                [
                    'role_id' => RoleNameEnum::VISITOR->value,
                    'role_name' => RoleNameEnum::VISITOR->name
                ],
            ]
        );
    }
}
