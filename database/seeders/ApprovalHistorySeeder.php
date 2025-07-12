<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Approval;
use App\Models\Document;
use App\Models\User;
use App\Models\ApprovalLevel;

class ApprovalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa dokumen dan user untuk testing
        $documents = Document::take(3)->get();
        $users = User::whereIn('role_id', [2,3,4])->take(2)->get(); // manager, section_head, admin
        $levels = ApprovalLevel::all();

        foreach($documents as $document) {
            foreach($users as $user) {
                foreach($levels as $level) {
                    Approval::create([
                        'document_id' => $document->id,
                        'user_id' => $user->id,
                        'level_id' => $level->id,
                        'status' => rand(0,1) ? 'approved' : 'rejected',
                        'notes' => 'Testing approval history',
                        'approved_at' => now()->subDays(rand(1,30)),
                    ]);
                }
            }
        }

        echo "Approval history data created successfully!\n";
    }
}
