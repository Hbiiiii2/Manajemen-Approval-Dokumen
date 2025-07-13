<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;

class DemoOrgSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        // 1. Department
        $departments = [
            ['name' => 'OPERASIONAL', 'code' => 'DOPR'],
            ['name' => 'KEUANGAN', 'code' => 'DKEU'],
            ['name' => 'PEMASARAN', 'code' => 'DPMS'],
        ];
        foreach ($departments as &$dept) {
            $dept['id'] = Department::create([
                'name' => $dept['name'],
                'description' => null
            ])->id;
        }

        // 2. Division
        $divisions = [
            ['name' => 'Proyek', 'code' => 'DOPR-PRO', 'department' => 'DOPR'],
            ['name' => 'Legal Teknis', 'code' => 'DOPR-LEG', 'department' => 'DOPR'],
            ['name' => 'Finance', 'code' => 'DKEU-FIN', 'department' => 'DKEU'],
            ['name' => 'HRD & GA', 'code' => 'DKEU-HRD', 'department' => 'DKEU'],
            ['name' => 'Marketing', 'code' => 'DPMS-MKT', 'department' => 'DPMS'],
            ['name' => 'Penjualan', 'code' => 'DPMS-SLS', 'department' => 'DPMS'],
        ];
        foreach ($divisions as &$div) {
            $deptId = collect($departments)->firstWhere('code', $div['department'])['id'];
            $div['id'] = Division::create([
                'name' => $div['name'],
                'description' => null,
                'department_id' => $deptId
            ])->id;
        }

        // 3. Roles
        $roles = Role::pluck('id', 'name');

        // 4. Dept Head per department
        foreach ($departments as $dept) {
            // Ambil divisi pertama dari departemen ini
            $firstDiv = collect($divisions)->first(function($div) use ($dept) {
                return $div['department'] === $dept['code'];
            });
            $firstName = $faker->firstName;
            $email = strtolower($firstName . '.dept.head.' . $dept['code'] . '@company.com');
            User::create([
                'name' => $firstName . ' (Dept Head ' . $dept['name'] . ')',
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => $roles['dept_head'],
                'division_id' => $firstDiv['id'], // Isi dengan divisi pertama
            ]);
        }

        // 5. Section Head & Staff per divisi
        foreach ($divisions as $div) {
            // Section Head
            $firstName = $faker->firstName;
            $email = strtolower($firstName . '.sect.head.' . $div['code'] . '@company.com');
            User::create([
                'name' => $firstName . ' (Section Head ' . $div['name'] . ')',
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => $roles['section_head'],
                'division_id' => $div['id'],
                'department_id' => null,
            ]);
            // 2 Staff
            for ($i = 1; $i <= 2; $i++) {
                $firstName = $faker->firstName;
                $email = strtolower($firstName . '.staff' . $i . '.' . $div['code'] . '@company.com');
                User::create([
                    'name' => $firstName . ' (Staff ' . $div['name'] . ' ' . $i . ')',
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role_id' => $roles['staff'],
                    'division_id' => $div['id'],
                    'department_id' => null,
                ]);
            }
        }

        // 6. Admin global
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'),
            'role_id' => $roles['admin'],
            'division_id' => null,
            'department_id' => null,
        ]);
    }
} 