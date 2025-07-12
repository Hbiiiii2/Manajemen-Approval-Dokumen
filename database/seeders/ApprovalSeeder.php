<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Approval;
use App\Models\Document;
use App\Models\User;
use App\Models\ApprovalLevel;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = Document::whereIn('status', ['approved', 'rejected'])->get();
        $users = User::whereIn('role_id', [2, 3])->get(); // manager dan section_head
        $approvalLevels = ApprovalLevel::all();
        
        if ($documents->isEmpty()) {
            $this->command->info('No approved/rejected documents found. Please run DocumentSeeder first.');
            return;
        }
        
        if ($users->isEmpty()) {
            $this->command->info('No manager/section_head users found. Please run UserSeeder first.');
            return;
        }
        
        if ($approvalLevels->isEmpty()) {
            $this->command->info('No approval levels found. Please run ApprovalLevelSeeder first.');
            return;
        }

        // Create sample approvals for approved/rejected documents
        foreach ($documents as $document) {
            $approver = $users->random();
            $level = $approvalLevels->where('name', ucfirst(str_replace('_', ' ', $approver->role->name)))->first();
            
            if ($level) {
                Approval::create([
                    'document_id' => $document->id,
                    'user_id' => $approver->id,
                    'level_id' => $level->id,
                    'status' => $document->status,
                    'notes' => $document->status === 'approved' ? 'Dokumen disetujui' : 'Dokumen ditolak karena tidak memenuhi persyaratan',
                    'approved_at' => now(),
                ]);
            }
        }
        
        $this->command->info('Approval seeder completed successfully!');
    }
} 