<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ProductionCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $branch = Branch::firstOrCreate(
            ['name' => 'TRB Automobile'],
            [
                'contact_number'   => '017 367 3385',
                'address'          => '70, Jalan PU 7/3, Taman Puchong Utama, 47100 Puchong, Selangor',
                'google_map_link'  => 'https://maps.app.goo.gl/WQsk6vup7nvio19c6',
                'opening_time'     => '09:00',
                'closing_time'     => '18:45',
                'service_capacity' => 3,
                'is_active'        => true,
            ]
        );

        $services = json_decode(file_get_contents(database_path('seeders/production_catalog.json')), true);

        foreach ($services as $data) {
            Service::withTrashed()->updateOrCreate(
                ['branch_id' => $branch->id, 'name' => $data['name']],
                [
                    'description'        => $data['description'],
                    'category'           => $data['category'],
                    'price'              => $data['price'],
                    'estimated_duration' => $data['estimated_duration'],
                    'meta_data'          => $data['meta_data'],
                    'image_path'         => $data['image_path'],
                    'is_active'          => true,
                    'deleted_at'         => null,
                ]
            );
        }
    }
}
