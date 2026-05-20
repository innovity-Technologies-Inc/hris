<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
