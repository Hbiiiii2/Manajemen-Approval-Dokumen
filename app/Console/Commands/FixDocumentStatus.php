<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Document;
use App\Models\Approval;

class FixDocumentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:fix-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix document status based on approval history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memperbaiki status dokumen...');
        
        $documents = Document::with(['user.role', 'approvals.user.role'])->get();
        $fixedCount = 0;
        
        foreach ($documents as $document) {
            $originalStatus = $document->status;
            $newStatus = $this->determineDocumentStatus($document);
            
            if ($originalStatus !== $newStatus) {
                $document->update(['status' => $newStatus]);
                $this->info("Dokumen ID {$document->id} ({$document->title}): {$originalStatus} → {$newStatus}");
                $fixedCount++;
            }
        }
        
        $this->info("Selesai! {$fixedCount} dokumen diperbaiki.");
    }
    
    private function determineDocumentStatus(Document $document)
    {
        $creatorRole = $document->user->role->name;
        
        // Cek apakah ada approval rejected
        $hasRejected = $document->approvals()
            ->where('status', 'rejected')
            ->exists();
            
        if ($hasRejected) {
            return 'rejected';
        }
        
        if ($creatorRole === 'section_head') {
            // Jika Section Head yang submit, cukup Dept Head approve
            $deptHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'dept_head');
                })->exists();

            if ($deptHeadApproval) {
                return 'approved';
            }
        } else {
            // Jika Staff yang submit, butuh Section Head dan Dept Head approve
            $sectionHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'section_head');
                })->exists();

            $deptHeadApproval = $document->approvals()
                ->where('status', 'approved')
                ->whereHas('user.role', function($q) {
                    $q->where('name', 'dept_head');
                })->exists();

            if ($sectionHeadApproval && $deptHeadApproval) {
                return 'approved';
            }
        }
        
        return 'pending';
    }
} 