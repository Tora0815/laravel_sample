<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // 日本語ロケールでfakerインスタンス生成
        $faker = \Faker\Factory::create('ja_JP');

        return [
            'name' => $faker->name(),                     // 例: 山田 太郎
            'email' => $faker->unique()->safeEmail(),     // 日本語メールではなく、ドメイン付き英語になるがOK
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => false,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(
            fn (array $attributes) => [
            'email_verified_at' => null,
            ]
        );
    }
}
