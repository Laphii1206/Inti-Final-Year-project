<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /*Seed the application's database.*/
    public function run(): void
    {
        // Disable foreign keys to safely truncate tables
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Branch::truncate();
        Service::truncate();
        Schema::enableForeignKeyConstraints();

        // Admin Account
        $admin = User::create([
            'name'          => 'Admin Admin',
            'email'         => 'admin@example.com',
            'phone'         => '012-3456789',
            'password'      => Hash::make('password'),
            'role'          => User::ROLE_ADMIN,
            'is_main_admin' => true,
        ]);

        // Mechanic Account
        $mechanic = User::create([
            'name'          => 'John Mechanic',
            'email'         => 'mechanic@example.com',
            'phone'         => '012-7654321',
            'password'      => Hash::make('password'),
            'role'          => User::ROLE_MECHANIC,
            'is_main_admin' => false,
        ]);

        // Customer Account
        $customer = User::create([
            'name'          => 'Alan Customer',
            'email'         => 'customer@example.com',
            'phone'         => '012-1111111',
            'password'      => Hash::make('password'),
            'role'          => User::ROLE_CUSTOMER,
            'is_main_admin' => false,
        ]);

        // 2. Seed Branches (Single TRB Automobile shop)
        $branch1 = Branch::create([
            'name'             => 'TRB Automobile',
            'contact_number'   => '017 367 3385',
            'address'          => '70, Jalan PU 7/3, Taman Puchong Utama, 47100 Puchong, Selangor',
            'google_map_link'  => 'https://maps.app.goo.gl/WQsk6vup7nvio19c6',
            'opening_time'     => '09:00',
            'closing_time'     => '18:45',
            'service_capacity' => 3,
            'is_active'        => true,
        ]);

        // 3. Seed Services under branches (with prices in RM, categories matching website)
        $servicesData = [
            [
                'name'               => 'Bosch Semi-Synthetic Engine Oil Service',
                'description'        => 'High performance oil service package including 4L Bosch semi-synthetic lubricant and standard oil filter change.',
                'category'           => 'engine oil',
                'price'              => 99.00,
                'estimated_duration' => 45,
            ],
            [
                'name'               => 'Bosch Fully-Synthetic Engine Oil Package',
                'description'        => 'Premium oil service package with 4L Bosch fully-synthetic lubricant, premium oil filter change, and 15-point inspection.',
                'category'           => 'engine oil',
                'price'              => 189.00,
                'estimated_duration' => 60,
            ],
            [
                'name'               => 'Continental UltraContact UC7 Tyre (15-inch)',
                'description'        => 'Premium passenger car tyre UC7 size 185/60R15. Price includes balancing and rubber valve replacement.',
                'category'           => 'tyre',
                'price'              => 195.00,
                'estimated_duration' => 30,
            ],
            [
                'name'               => 'Continental MaxContact MC6 Tyre (17-inch)',
                'description'        => 'Ultra-high performance tyre size 215/45R17. Engineered for maximum grip, braking performance, and handling.',
                'category'           => 'tyre',
                'price'              => 345.00,
                'estimated_duration' => 30,
            ],
            [
                'name'               => 'ClearShield Nano-Ceramic Tinting Film',
                'description'        => 'Full car tinting film package. Blocks 80% Infrared Radiation (IRR) and 99% UV rays. JPJ compliant.',
                'category'           => 'tinting film',
                'price'              => 159.00,
                'estimated_duration' => 120,
            ],
            [
                'name'               => 'Premium ClearShield Carbon Window Tint',
                'description'        => 'Luxury carbon ceramic blend film IRR 95%, UVR 99.9%. Ultimate heat reduction with lifetime warranty.',
                'category'           => 'tinting film',
                'price'              => 499.00,
                'estimated_duration' => 180,
            ],
            [
                'name'               => 'Bosch Advantage Front Wiper Blades',
                'description'        => 'Standard hook-type graphite coated rubber wiper blades. Price includes professional installation.',
                'category'           => 'wiper',
                'price'              => 39.00,
                'estimated_duration' => 15,
            ],
            [
                'name'               => '70mai Dashcam A800S Dual-Vision 4K',
                'description'        => 'Ultra 4K front dashcam and 1080P rear recording camera. Price includes hardwiring installation and 64GB SD card.',
                'category'           => 'dashcam',
                'price'              => 599.00,
                'estimated_duration' => 90,
            ],
        ];

        // Seed services for each branch
        $branches = [$branch1];
        foreach ($branches as $branch) {
            foreach ($servicesData as $service) {
                // Slightly randomize prices per branch for realistic variety
                $priceModifier = rand(-10, 10);
                $finalPrice = max(20.00, $service['price'] + $priceModifier);

                Service::create([
                    'branch_id'          => $branch->id,
                    'name'               => $service['name'],
                    'description'        => $service['description'],
                    'category'           => $service['category'],
                    'price'              => $finalPrice,
                    'estimated_duration' => $service['estimated_duration'],
                    'is_active'          => true,
                ]);
            }
        }
    }
}
