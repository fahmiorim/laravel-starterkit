<?php

namespace Database\Seeders;

use App\Models\Donor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DonorSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $bloodTypes = ['A', 'B', 'AB', 'O'];
        $genders = ['Laki-laki', 'Perempuan'];
        $rhesusTypes = ['+', '-'];

        // Create 50 random donors
        for ($i = 0; $i < 50; $i++) {
            $gender = $faker->randomElement($genders);
            $birthDate = $faker->dateTimeBetween('-60 years', '-18 years');
            $lastDonationDate = $faker->optional(0.7)->dateTimeBetween('-2 years', 'now');
            $hasKta = $faker->boolean(80); // 80% chance of having a KTA
            
            $donorData = [
                'name' => $faker->name($gender === 'Laki-laki' ? 'male' : 'female'),
                'nik' => $faker->unique()->numerify('32##############'),
                'gender' => $gender,
                'birth_date' => $birthDate->format('Y-m-d'),
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
                'blood_type' => $faker->randomElement($bloodTypes),
                'rhesus' => $faker->randomElement($rhesusTypes),
                'last_donation_date' => $lastDonationDate ? $lastDonationDate->format('Y-m-d') : null,
                'total_donations' => $lastDonationDate ? $faker->numberBetween(1, 20) : 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Add KTA data if applicable
            if ($hasKta) {
                $donorData['kta_number'] = 'KTA-' . $faker->unique()->numberBetween(1000, 9999);
                $donorData['kta_issued_at'] = $faker->dateTimeBetween('-2 years', 'now');
                $donorData['qr_code_path'] = 'qrcodes/' . $faker->uuid . '.png';
            }
            
            Donor::create($donorData);
        }
    }
}
