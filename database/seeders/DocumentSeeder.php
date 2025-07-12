<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $documentTypes = DocumentType::all();
        
        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }
        
        if ($documentTypes->isEmpty()) {
            $this->command->info('No document types found. Please run DocumentTypeSeeder first.');
            return;
        }

        $statuses = ['pending', 'approved', 'rejected'];
        
        // Create sample documents with more realistic data
        $sampleDocuments = [
            [
                'title' => 'Surat Permohonan Cuti Tahunan',
                'description' => 'Permohonan cuti tahunan untuk periode Juli 2024',
                'status' => 'pending'
            ],
            [
                'title' => 'Laporan Keuangan Q2 2024',
                'description' => 'Laporan keuangan triwulan kedua tahun 2024',
                'status' => 'approved'
            ],
            [
                'title' => 'Proposal Pengembangan Sistem',
                'description' => 'Proposal pengembangan sistem manajemen dokumen',
                'status' => 'pending'
            ],
            [
                'title' => 'Kontrak Kerja Karyawan Baru',
                'description' => 'Kontrak kerja untuk posisi Software Developer',
                'status' => 'approved'
            ],
            [
                'title' => 'Laporan Aktivitas Bulanan',
                'description' => 'Laporan aktivitas dan pencapaian bulan Juni 2024',
                'status' => 'rejected'
            ],
            [
                'title' => 'Surat Permohonan Reimbursement',
                'description' => 'Permohonan reimbursement biaya transportasi',
                'status' => 'pending'
            ],
            [
                'title' => 'Laporan Audit Internal',
                'description' => 'Laporan audit internal departemen IT',
                'status' => 'approved'
            ],
            [
                'title' => 'Proposal Budget 2025',
                'description' => 'Proposal anggaran tahun 2025',
                'status' => 'pending'
            ],
            [
                'title' => 'Kontrak Vendor',
                'description' => 'Kontrak kerja sama dengan vendor IT',
                'status' => 'approved'
            ],
            [
                'title' => 'Laporan Evaluasi Kinerja',
                'description' => 'Laporan evaluasi kinerja karyawan',
                'status' => 'rejected'
            ]
        ];

        foreach ($sampleDocuments as $index => $document) {
            $user = $users->random();
            $documentType = $documentTypes->random();
            
            Document::create([
                'user_id' => $user->id,
                'document_type_id' => $documentType->id,
                'title' => $document['title'],
                'file_path' => 'documents/sample_' . ($index + 1) . '.pdf',
                'description' => $document['description'],
                'status' => $document['status'],
            ]);
        }
        
        $this->command->info('Document seeder completed successfully!');
    }
} 