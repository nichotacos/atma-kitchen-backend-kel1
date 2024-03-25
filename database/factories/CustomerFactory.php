<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nomor_telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(),
            'password' => fake()->word(1),
            'tanggal_registrasi' => fake()->date('Y-m-d', $max = 'now'),
            'tanggal_lahir' => fake()->date('Y-m-d', $max = 'now'),
            'poin' => fake()->numberBetween(25, 100),
            'saldo' => 50000.00
        ];
    }
}
