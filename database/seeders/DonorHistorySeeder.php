<?php

namespace Database\Seeders;

use App\Models\Donor;
use App\Models\DonationSchedule;
use App\Models\DonorHistory;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DonorHistorySeeder extends Seeder
{
    public function run()
    {
        // Get the first 20 donors
        $donors = Donor::take(20)->get();
        
        // Get some donation schedules
        $schedules = DonationSchedule::take(5)->pluck('id')->toArray();
        
        $locations = [
            'PMI Pusat Jakarta',
            'PMI Kota Bandung',
            'PMI Kota Surabaya',
            'PMI Kota Yogyakarta',
            'PMI Kota Semarang',
            'PMI Kota Medan',
            'PMI Kota Makassar',
            'PMI Kota Denpasar',
        ];
        
        foreach ($donors as $donor) {
            $donationDate = Carbon::now()->subMonths(rand(1, 24));
            
            // First donation
            DonorHistory::create([
                'donor_id' => $donor->id,
                'blood_donation_schedule_id' => count($schedules) > 0 ? $schedules[array_rand($schedules)] : null,
                'tanggal_donor' => $donationDate->format('Y-m-d'),
                'lokasi' => $locations[array_rand($locations)],
                'jumlah_kantong' => 1,
                'status' => 'valid',
                'note' => 'Donor darah rutin',
                'created_at' => $donationDate,
                'updated_at' => $donationDate,
            ]);
            
            // Create another donation 3-6 months after the first one
            $secondDonation = $donationDate->copy()->addMonths(rand(3, 6));
            
            if ($secondDonation < now()) {
                DonorHistory::create([
                    'donor_id' => $donor->id,
                    'blood_donation_schedule_id' => count($schedules) > 0 ? $schedules[array_rand($schedules)] : null,
                    'tanggal_donor' => $secondDonation->format('Y-m-d'),
                    'lokasi' => $locations[array_rand($locations)],
                    'jumlah_kantong' => 1,
                    'status' => 'valid',
                    'note' => 'Donor darah rutin lanjutan',
                    'created_at' => $secondDonation,
                    'updated_at' => $secondDonation,
                ]);
            }
        }
    }
}
