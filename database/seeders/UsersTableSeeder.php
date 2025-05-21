<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // 管理者ユーザーを1件作成（存在しないときのみ）
        if (!User::where('email', 'webmaster@localhost.localdomain')->exists()) {
            User::create(
                [
                'name' => '管理者',
                'email' => 'webmaster@localhost.localdomain',
                'password' => Hash::make('P@ssw0rd'),
                'remember_token' => Str::random(10),
                ]
            );
        }

        // ユニークなダミーユーザーを5件追加
        for ($i = 1; $i <= 5; $i++) {
            $email = "dummy{$i}@example.com";

            // 重複チェックしてから作成
            if (!User::where('email', $email)->exists()) {
                User::create(
                    [
                    'name' => "ダミーユーザー{$i}",
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                    ]
                );
            }
        }
    }
}
