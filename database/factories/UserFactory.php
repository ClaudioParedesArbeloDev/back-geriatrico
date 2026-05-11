<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name'               => fake()->firstName(),
            'last_name'          => fake()->lastName(),
            'dni'                => fake()->unique()->numerify('########'),
            'birth_date'         => fake()->date('Y-m-d', '-25 years'),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'phone'              => fake()->phoneNumber(),
            'employee_code'      => fake()->unique()->numerify('EMP####'),
            'hire_date'          => fake()->date(),
            'is_active'          => true,
            'password'           => static::$password ??= Hash::make('password'),
            'remember_token'     => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

  
    public function withLicense(): static
    {
        return $this->state(fn () => [
            'license_number' => fake()->numerify('MP######'),
        ]);
    }
}
