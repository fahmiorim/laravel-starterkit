<?php

namespace Database\Seeders;

use App\Models\BloodStock;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BloodStockSeeder extends Seeder
{
    public function run(): void
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['+', '-'];
        
        foreach ($bloodTypes as $bloodType) {
            foreach ($rhesusTypes as $rhesus) {
                $quantity = rand(5, 20);
                $donationDate = Carbon::now()->subDays(rand(1, 30));
                $expiryDate = $donationDate->copy()->addDays(35);
                
                BloodStock::updateOrCreate(
                    [
                        'blood_type' => $bloodType,
                        'rhesus' => $rhesus,
                    ],
                    [
                        'quantity' => $quantity,
                        'donation_date' => $donationDate,
                        'expiry_date' => $expiryDate,
                        'updated_by' => 1, // Admin user ID
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
