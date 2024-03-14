<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Supplier::factory(10)->create();
        \App\Models\Product::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'code' => mt_rand(0, 9999),
            'telephone' => fake()->phoneNumber(),
            'profilePhoto' => fake()->imageUrl(640, 480, 'animals', true),
            'roles' => 'Admin',
            'email' => 'ridhoawwaludin@gmail.com',
            'address' => fake()->address(),
            'password' => Hash::make('password') //pass
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Cashier',
            'code' => mt_rand(0, 9999),
            'telephone' => fake()->phoneNumber(),
            'profilePhoto' => fake()->imageUrl(640, 480, 'animals', true),
            'roles' => 'Cashier',
            'email' => 'bpertiwi@example.org',
            'address' => fake()->address(),
            'password' => Hash::make('password') //pass

        ]);
    }
}
