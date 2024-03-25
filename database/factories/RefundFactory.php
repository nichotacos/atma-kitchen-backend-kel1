<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Refund>
 */
class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_status' =>$this->faker->numberBetween(12,13),
            'id_customer' =>$this->faker->numberBetween(1,5),
            'nama_bank_tujuan' => 'BCA',
            'no_rekening_tujuan' =>$this->faker->unique()->bankAccountNumber,
            'nominal_refund' =>$this->faker->numberBetween(500,100000),
            'tanggal_refund' => $this->faker->date('Y-m-d'),
        ];
    }
}
