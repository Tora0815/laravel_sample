<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;
use Faker\Factory as Faker;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ja_JP');
        $users = User::all();

        foreach ($users as $user) {
            if ($user->id === 1) {
                // 管理者用（固定値）
                Profile::create(
                    [
                    'u_id'       => $user->id,
                    'yubin'      => '100-0001',
                    'jusho1'     => '東京都',
                    'jusho2'     => '千代田区1-1',
                    'jusho3'     => '管理センタービル101',
                    'tel'        => '03-1234-5678',
                    'biko'       => '管理者プロフィール',
                    'type_flag'  => 0,
                    'kanri_flag' => 1, // 管理者だけフラグ立てる想定
                    ]
                );
            } else {
                // ダミーユーザー用（faker）
                Profile::create(
                    [
                    'u_id'       => $user->id,
                    'yubin'      => $faker->postcode(),
                    'jusho1'     => $faker->prefecture(),
                    'jusho2'     => $faker->city() . $faker->streetAddress(),
                    'jusho3'     => $faker->secondaryAddress(),
                    'tel'        => $faker->phoneNumber(),
                    'biko'       => '自動生成されたダミーユーザープロフィール',
                    'type_flag'  => 0,
                    'kanri_flag' => 0,
                    ]
                );
            }
        }
    }
}
