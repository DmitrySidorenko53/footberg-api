<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportedLocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('supported_locales')->insertOrIgnore(
            [
                ['locale' => 'en'],
                ['locale' => 'ru']
            ]
        );
    }
}
