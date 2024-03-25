<?php

namespace Database\Factories;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = Arr::shuffle([2, 3, 4]);

        return [
            'id_role' => array_shift($roles), 
            'nama_karyawan' => $this->faker->name,
            'nomor_telepon_karyawan' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->unique()->userName,
            'password' => 'password', 
            'tanggal_rekrut' => $this->faker->date('2023-m-d'),
            'gaji_harian' => $this->faker->numberBetween(50000, 100000),
            'bonus_rajin' => $this->faker->numberBetween(0, 50000),
        ];

    }
}
