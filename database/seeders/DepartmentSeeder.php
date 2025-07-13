<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->truncate();
        DB::table('departments')->insert([
            [
                'name' => 'OPERASIONAL',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KEUANGAN',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PEMASARAN',
                'description' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
} 