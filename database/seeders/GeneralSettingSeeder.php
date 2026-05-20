<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the storage directory exists
        $storagePath = storage_path('app/public/assets/images');
        if (!File::isDirectory($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        // Copy assets from public to storage
        $logoSource = public_path('assets/images/logo.png');
        $faviconSource = public_path('assets/images/favicon.png');

        if (File::exists($logoSource)) {
            File::copy($logoSource, $storagePath . '/logo.png');
        }

        if (File::exists($faviconSource)) {
            File::copy($faviconSource, $storagePath . '/favicon.png');
        }

        DB::table('general_settings')->truncate();
        DB::table('general_settings')->insert([
            'name' => 'HRIS',
            'currency' => 'BDT',
            'logo_light' => 'assets/images/logo.png',
            'logo_dark' => 'assets/images/logo.png',
            'favicon' => 'assets/images/favicon.png',
            'branch_status' => 1,
            'division_status' => 1,
            'department_status' => 1,
            'section_status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
