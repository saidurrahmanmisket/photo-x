<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BookingSetting;

class BookingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'vendor_print_limit',
                'value' => '10',
                'description' => 'Default print limit for vendor bookings'
            ],
            [
                'key' => 'default_booking_duration',
                'value' => '24',
                'description' => 'Default booking duration in hours'
            ],
            [
                'key' => 'max_prints_per_booking',
                'value' => '50',
                'description' => 'Maximum prints allowed per booking'
            ],
        ];

        foreach ($settings as $setting) {
            BookingSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
} 