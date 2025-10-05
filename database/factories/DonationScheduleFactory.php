<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DonationSchedule>
 */
class DonationScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $judul = 'Donor Darah Rutin: ' . $this->faker->company;
        return [
            'judul' => $judul,
            'slug' => Str::slug($judul),
            'deskripsi' => $this->faker->paragraph,
            'lokasi' => $this->faker->address,
            'tanggal_mulai' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'tanggal_selesai' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'penanggung_jawab' => $this->faker->name,
            'status' => $this->faker->randomElement(['draft', 'published', 'canceled']),
        ];
    }
}