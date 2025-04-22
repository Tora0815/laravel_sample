<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $init_users = [
            [
                'name' => '管理者',
                'email' => 'webmaster@localhost.localdomain',
                'password' => 'P@ssw0rd',
            ]
        ];

        foreach ($init_users as $init_user) {
            $user = new User();
            $user->name = $init_user['name'];
            $user->email = $init_user['email'];
            $user->password = Hash::make($init_user['password']);
            $user->save();
        }
    }
}
