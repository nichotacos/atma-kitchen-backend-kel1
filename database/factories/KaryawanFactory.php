<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'id_role' => fake()->numberBetween(1, 4),
            'nama_karyawan' => fake()->name(),
            'nomor_telepon_karyawan' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'username' => fake()->unique()->userName(),
            'password' => fake()->word(1),
            'tanggal_rekrut' => fake()->date('Y-m-d', $max = '-5 years'),
            'gaji_harian' => function (array $attributes) {
                if ($attributes['id_role'] == 1) {
                    return 0;
                } else {
                    $baseSalary = 40000;
                    return ($attributes['id_role'] * $baseSalary);
                }
            },
            'bonus_rajin' => function (array $attributes) {
                if ($attributes['id_role'] == 1) {
                    return 0;
                } else {
                    $bonus = 25000;
                    return ($attributes['id_role'] * $bonus);
                }
            },
        ];
    }
}
