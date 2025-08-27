<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategori = ['makanan', 'minuman', 'lainnya'];
        
        return [
            'nama_produk' => $this->faker->words(3, true),
            'deskripsi' => $this->faker->sentence(10),
            'harga' => $this->faker->randomFloat(2, 10000, 1000000),
            'kategori' => $this->faker->randomElement($kategori),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }
}
