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

        $roles = ['Admin', 'Cashier', 'Customer'];

        return [
            'name' => fake()->name(),
            'telephone' => fake()->phoneNumber(),
            'profilePhoto' => fake()->imageUrl(640, 480, 'animals', true),
            'roles' => $roles[rand(0,2)],
            'email' => fake()->safeEmail(),
            'password' => Hash::make('password') //password
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
