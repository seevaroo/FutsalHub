<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\TimeSlot;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin dan Customer
        User::updateOrCreate(
            ['email' => 'admin@futsal.com'],
            [
                'name' => 'Administrator FutsalHub',
                'phone' => '081234567890',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'phone' => '089876543210',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // 2. Data Master Lapangan
        $fields = [
            [
                'name' => 'Lapangan 1 (Vinyl)',
                'type' => 'vinyl',
                'price_per_hour' => 150000,
                'description' => 'Lapangan berstandar FIFA dengan lantai vinyl impor berkualitas tinggi, empuk, dan anti-selip. Dilengkapi sistem penerangan LED profesional.',
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 2 (Sintetis)',
                'type' => 'sintetis',
                'price_per_hour' => 120000,
                'description' => 'Rumput sintetis monofilamen lembut setebal 5cm dengan taburan butiran karet tebal. Sensasi bermain layaknya di lapangan stadion asli.',
                'status' => 'available',
            ],
            [
                'name' => 'Lapangan 3 (Interlock)',
                'type' => 'interlock',
                'price_per_hour' => 175000,
                'description' => 'Lantai modular interlocking polymer tahan benturan dengan daya cengkeram tinggi dan ventilasi lubang pengurang hawa panas.',
                'status' => 'available',
            ],
        ];

        foreach ($fields as $fieldData) {
            Field::updateOrCreate(
                ['name' => $fieldData['name']],
                $fieldData
            );
        }

        // 3. Data Slot Jam Operasional (08:00 - 22:00)
        $startHour = 8;
        $endHour = 22;

        for ($h = $startHour; $h < $endHour; $h++) {
            $start = sprintf('%02d:00:00', $h);
            $end = sprintf('%02d:00:00', $h + 1);

            TimeSlot::updateOrCreate(
                ['start_time' => $start, 'end_time' => $end],
                ['start_time' => $start, 'end_time' => $end]
            );
        }
    }
}
