<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profiles')->insert(
            [
            'u_id' => 1, // UsersTableSeederで作成したユーザーIDと一致させる
            'yubin' => '100-0001',
            'jusho1' => '東京都',
            'jusho2' => '千代田区千代田1-1',
            'jusho3' => '皇居前ビル101',
            'tel' => '03-1234-5678',
            'biko' => '備考テストデータ',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            ]
        );
    }
}
