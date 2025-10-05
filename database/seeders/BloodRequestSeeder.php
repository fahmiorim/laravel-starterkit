<?php

namespace Database\Seeders;

use App\Models\BloodRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BloodRequestSeeder extends Seeder
{
    public function run()
    {
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $rhesusTypes = ['+', '-'];
        $hospitals = [
            'RS Umum Daerah', 'RS Siloam', 'RS Premier', 'RS EMC', 'RS Hermina',
            'RS Mitra Keluarga', 'RS Panti Rapih', 'RS Panti Wilis', 'RS Islam', 'RS Kariadi'
        ];
        
        $statuses = ['pending', 'approved', 'rejected', 'completed'];
        
        for ($i = 0; $i < 20; $i++) {
            $bloodType = $bloodTypes[array_rand($bloodTypes)];
            $rhesus = $rhesusTypes[array_rand($rhesusTypes)];
            $status = $statuses[array_rand($statuses)];
            
            BloodRequest::create([
                'hospital_name' => $hospitals[array_rand($hospitals)],
                'patient_name' => 'Pasien ' . ($i + 1),
                'blood_type' => $bloodType,
                'rhesus' => $rhesus,
                'quantity' => rand(1, 5),
                'status' => $status,
                'notes' => $status === 'rejected' ? 'Stok darah tidak mencukupi' : null,
                'processed_by' => $status !== 'pending' ? 1 : null, // Admin user ID if not pending
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
