<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('approval_levels')->insert([
            ['name' => 'Dept Head', 'level' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Section Head', 'level' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
