<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'Divisi Teknologi Informasi',
                'status' => 'active',
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'Divisi Sumber Daya Manusia',
                'status' => 'active',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Divisi Keuangan',
                'status' => 'active',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Divisi Pemasaran',
                'status' => 'active',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Divisi Operasional',
                'status' => 'active',
            ],
            [
                'name' => 'Sales',
                'code' => 'SALES',
                'description' => 'Divisi Penjualan',
                'status' => 'active',
            ],
            [
                'name' => 'Legal',
                'code' => 'LEGAL',
                'description' => 'Divisi Hukum',
                'status' => 'active',
            ],
            [
                'name' => 'Research & Development',
                'code' => 'RND',
                'description' => 'Divisi Penelitian dan Pengembangan',
                'status' => 'active',
            ],
            [
                'name' => 'Customer Service',
                'code' => 'CS',
                'description' => 'Divisi Layanan Pelanggan',
                'status' => 'active',
            ],
            [
                'name' => 'Quality Assurance',
                'code' => 'QA',
                'description' => 'Divisi Jaminan Kualitas',
                'status' => 'active',
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
