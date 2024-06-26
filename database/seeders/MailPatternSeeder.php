<?php

namespace Database\Seeders;

use App\Models\MailPattern;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailPatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('mail_patterns')->insertOrIgnore([
            'scope' => 'confirmation',
            'subject' => 'Подтвердите аккаунт',
            'title' => 'Спасибо за регистрацию в приложении “Футберг”!',
            'body' => 'Для завершения регистрации в приложении “Футберг” Вам необходимо активировать учетную запись с помощью подтверждения электронной почты.',
            'footer' => 'команда "Футберг"'
        ]);
    }
}
