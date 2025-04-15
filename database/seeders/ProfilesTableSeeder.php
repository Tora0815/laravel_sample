<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfilesTableSeeder extends Seeder
{
    public function run(): void
    {
        Profile::create(
            [
            'user_id'  => 1,
            'name'     => '管理者',
            'gender'   => 1,
            'birthday' => '1990-01-01',
            ]
        );
    }
}
