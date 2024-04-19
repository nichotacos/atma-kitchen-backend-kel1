<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penggajian>
 */
class PenggajianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_karyawan' =>$this->faker->numberBetween(1,5),
            'jumlah_hadir' =>$this->faker->numberBetween(1,10),
            'jumlah_bolos' =>$this->faker->numberBetween(1,10),
            'bonus' =>$this->faker->numberBetween(500,10000),
            'tanggal_penggajian' => $this->faker->date('2024-m-d'),
            'total_gaji' => $this->faker->numberBetween(1000,1000000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
