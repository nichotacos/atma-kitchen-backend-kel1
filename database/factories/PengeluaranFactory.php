<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_jenis_pengeluaran' =>$this->faker->numberBetween(1,5),
            'tanggal_pengeluaran' => $this->faker->date('Y-m-d'),
            'nominal_pengeluaran' => $this->faker->numberBetween(1000,1000000),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
