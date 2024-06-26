<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MailPatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mail_patterns')->insert([
            [
                'scope' => 'confirmation',
                'subject' => 'Подтвердите аккаунт',
                'title' => 'Спасибо за регистрацию в приложении “Футберг”!',
                'body' => 'Для завершения регистрации в приложении “Футберг” Вам необходимо активировать учетную запись с помощью подтверждения электронной почты.',
                'footer' => 'команда "Футберг"'
            ],
            [
                'scope' => 'reset',
                'subject' => 'Сброс пароля',
                'title' => 'Мы получили от Вас запрос на сброс текущего пароля.',
                'body' => 'Для сброса пароля Вам необходимо использовать разовый код высланный настоящим сообщением.',
                'footer' => 'команда "Футберг"'
            ]
        ]);
    }
}
