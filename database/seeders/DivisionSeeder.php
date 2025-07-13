<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisions')->truncate();
        $departments = DB::table('departments')->get()->keyBy('name');
        $divisions = [
            ['name' => 'Proyek', 'code' => 'DOPR-PRO', 'description' => 'Mengelola perencanaan & pelaksanaan konstruksi', 'department_id' => $departments['OPERASIONAL']->id],
            ['name' => 'Legal Teknis', 'code' => 'DOPR-LEG', 'description' => 'Mengurus legalitas proyek: IMB, Amdal, SLF, HGB, kontrak', 'department_id' => $departments['OPERASIONAL']->id],
            ['name' => 'Finance', 'code' => 'DKEU-FIN', 'description' => 'Arus kas, laporan keuangan, perpajakan proyek', 'department_id' => $departments['KEUANGAN']->id],
            ['name' => 'HRD & GA', 'code' => 'DKEU-HRD', 'description' => 'SDM, kontrak kerja, pengadaan alat dan kendaraan proyek', 'department_id' => $departments['KEUANGAN']->id],
            ['name' => 'Marketing', 'code' => 'DPMS-MKT', 'description' => 'Promosi produk properti, branding, digital campaign', 'department_id' => $departments['PEMASARAN']->id],
            ['name' => 'Penjualan', 'code' => 'DPMS-SLS', 'description' => 'Penjualan unit properti, manajemen tim sales & customer service', 'department_id' => $departments['PEMASARAN']->id],
        ];
        DB::table('divisions')->insert($divisions);
    }
}
