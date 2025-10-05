<?php

namespace Database\Seeders;

use App\Models\DonationSchedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DonationScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'judul' => 'Donor Darah Rutin PMI Kota',
                'deskripsi' => 'Donor darah rutin setiap bulan di kantor PMI Kota',
                'lokasi' => 'Kantor PMI Kota, Jl. Contoh No. 123',
                'tanggal_mulai' => Carbon::now()->addDays(5)->setTime(9, 0, 0),
                'tanggal_selesai' => Carbon::now()->addDays(5)->setTime(15, 0, 0),
                'penanggung_jawab' => 'Admin PMI',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Donor Darah Keliling - Mall Center',
                'deskripsi' => 'Kegiatan donor darah keliling di Mall Center',
                'lokasi' => 'Mall Center, Lantai 1, Jl. Pusat Perbelanjaan No. 45',
                'tanggal_mulai' => Carbon::now()->addDays(10)->setTime(10, 0, 0),
                'tanggal_selesai' => Carbon::now()->addDays(10)->setTime(17, 0, 0),
                'penanggung_jawab' => 'Tim Donor Darah',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Donor Darah Kampus',
                'deskripsi' => 'Kegiatan donor darah di Kampus Merdeka',
                'lokasi' => 'Aula Kampus Merdeka, Jl. Pendidikan No. 67',
                'tanggal_mulai' => Carbon::now()->addDays(15)->setTime(8, 0, 0),
                'tanggal_selesai' => Carbon::now()->addDays(15)->setTime(14, 0, 0),
                'penanggung_jawab' => 'BEM Kampus Merdeka',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($schedules as $schedule) {
            DonationSchedule::firstOrCreate(
                ['judul' => $schedule['judul']],
                $schedule
            );
        }
    }
}