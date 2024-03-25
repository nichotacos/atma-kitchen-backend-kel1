<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
            'nama' => $this->faker->name,
            'nomor_telepon' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password' => 'password', 
            'tanggal_registrasi' => $this->faker->date,
            'tanggal_lahir' => $this->faker->date,
            'poin' => $this->faker->numberBetween(0, 1000),
            'saldo' => $this->faker->randomFloat(2, 0, 10000),
        ];
    }
}
