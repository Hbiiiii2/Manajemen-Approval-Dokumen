<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Division;
use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Approval;
use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class PropertyApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // 1. Tambah DocumentType properti
        $propertyTypes = [
            'Sertifikat Tanah',
            'IMB (Izin Mendirikan Bangunan)',
            'PBB (Pajak Bumi & Bangunan)',
            'Akta Jual Beli',
            'Surat Keterangan Waris',
            'Perjanjian Sewa',
            'Site Plan',
            'Gambar Bangunan',
        ];
        foreach ($propertyTypes as $type) {
            DocumentType::firstOrCreate(['name' => $type]);
        }

        // 2. Tambah Divisi & Departemen properti jika belum ada
        $departments = [
            'Legal', 'Marketing', 'Keuangan', 'Operasional', 'Developer'
        ];
        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept]);
        }
        $divisions = [
            ['name' => 'Legal Properti', 'code' => 'PROP-LEG', 'description' => 'Urusan legalitas properti', 'department' => 'Legal'],
            ['name' => 'Marketing Properti', 'code' => 'PROP-MKT', 'description' => 'Promosi & penjualan properti', 'department' => 'Marketing'],
            ['name' => 'Keuangan Properti', 'code' => 'PROP-KEU', 'description' => 'Keuangan & pajak properti', 'department' => 'Keuangan'],
            ['name' => 'Operasional Properti', 'code' => 'PROP-OPS', 'description' => 'Operasional proyek properti', 'department' => 'Operasional'],
            ['name' => 'Developer', 'code' => 'PROP-DEV', 'description' => 'Pengembang proyek', 'department' => 'Developer'],
        ];
        foreach ($divisions as $div) {
            $dept = Department::where('name', $div['department'])->first();
            if ($dept) {
                Division::firstOrCreate([
                    'name' => $div['name'],
                    'code' => $div['code'],
                    'description' => $div['description'],
                    'department_id' => $dept->id
                ]);
            }
        }

        // 3. Tambah user (staff, section_head, dept_head) di divisi properti
        $roles = Role::all()->keyBy('name');
        $divisiProperti = Division::where('code', 'LIKE', 'PROP-%')->get();
        foreach ($divisiProperti as $div) {
            // Dept Head
            $deptHead = User::firstOrCreate([
                'email' => 'depthead.' . strtolower($div->code) . '@properti.com',
            ], [
                'name' => 'Dept Head ' . $div->name,
                'password' => Hash::make('password'),
                'role_id' => $roles['dept_head']->id ?? 3,
                'division_id' => $div->id,
            ]);
            // Section Head
            $sectionHead = User::firstOrCreate([
                'email' => 'sectionhead.' . strtolower($div->code) . '@properti.com',
            ], [
                'name' => 'Section Head ' . $div->name,
                'password' => Hash::make('password'),
                'role_id' => $roles['section_head']->id ?? 2,
                'division_id' => $div->id,
            ]);
            // Staff
            for ($i = 1; $i <= 3; $i++) {
                User::firstOrCreate([
                    'email' => 'staff' . $i . '.' . strtolower($div->code) . '@properti.com',
                ], [
                    'name' => 'Staff ' . $i . ' ' . $div->name,
                    'password' => Hash::make('password'),
                    'role_id' => $roles['staff']->id ?? 1,
                    'division_id' => $div->id,
                ]);
            }
        }

        // 4. Tambah dokumen properti dan approval dummy
        $documentTypes = DocumentType::whereIn('name', $propertyTypes)->get();
        $approvalLevels = ApprovalLevel::all();
        foreach ($divisiProperti as $div) {
            $staffs = User::where('division_id', $div->id)->whereHas('role', function($q){ $q->where('name', 'staff'); })->get();
            $sectionHead = User::where('division_id', $div->id)->whereHas('role', function($q){ $q->where('name', 'section_head'); })->first();
            $deptHead = User::where('division_id', $div->id)->whereHas('role', function($q){ $q->where('name', 'dept_head'); })->first();
            foreach ($staffs as $staff) {
                $docType = $documentTypes->random();
                $doc = Document::create([
                    'user_id' => $staff->id,
                    'division_id' => $div->id,
                    'document_type_id' => $docType->id,
                    'title' => $docType->name . ' ' . $faker->city,
                    'description' => $faker->sentence(8),
                    'status' => 'pending',
                ]);
                // Approval oleh Section Head
                if ($sectionHead) {
                    Approval::create([
                        'document_id' => $doc->id,
                        'user_id' => $sectionHead->id,
                        'level_id' => $approvalLevels->where('name', 'Section Head')->first()->id ?? 2,
                        'status' => 'approved',
                        'notes' => 'Disetujui oleh Section Head',
                        'approved_at' => now(),
                    ]);
                }
                // Approval oleh Dept Head
                if ($deptHead) {
                    Approval::create([
                        'document_id' => $doc->id,
                        'user_id' => $deptHead->id,
                        'level_id' => $approvalLevels->where('name', 'Dept Head')->first()->id ?? 1,
                        'status' => 'approved',
                        'notes' => 'Disetujui oleh Dept Head',
                        'approved_at' => now(),
                    ]);
                }
            }
        }
    }
} 