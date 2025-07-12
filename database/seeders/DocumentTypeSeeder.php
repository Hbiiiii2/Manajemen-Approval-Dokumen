<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentType;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documentTypes = [
            'Surat Permohonan',
            'Laporan Keuangan',
            'Proposal Bisnis',
            'Kontrak Kerja',
            'Laporan Aktivitas',
        ];

        foreach ($documentTypes as $documentType) {
            DocumentType::create([
                'name' => $documentType
            ]);
        }

        $this->command->info('DocumentType seeder completed successfully!');
    }
} 