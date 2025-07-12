<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;
use App\Models\DocumentType;
use App\Models\Division;
use App\Models\Role;
use Faker\Factory as Faker;
use App\Models\Approval;
use App\Models\ApprovalLevel;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $divisions = Division::all();
        $documentTypes = DocumentType::all();
        $approvalLevels = ApprovalLevel::all();
        
        // Get staff role by name
        $staffRole = Role::where('name', 'staff')->first();
        if (!$staffRole) {
            echo "Staff role not found. Skipping document creation.\n";
            return;
        }

        // Document templates per division (using division code as key)
        $divisionDocuments = [
            'IT' => [
                'Sistem Update Proposal',
                'Infrastructure Maintenance Report',
                'Software Development Plan',
                'Network Security Audit',
                'Database Migration Plan',
                'Cloud Migration Strategy',
                'IT Budget Proposal',
                'System Integration Report',
                'Cybersecurity Policy',
                'Technology Roadmap',
            ],
            'HR' => [
                'Employee Handbook Update',
                'Recruitment Strategy Plan',
                'Training Program Proposal',
                'Performance Review Policy',
                'Compensation Structure',
                'Employee Benefits Plan',
                'Workplace Safety Policy',
                'Diversity & Inclusion Plan',
                'HR Budget Proposal',
                'Employee Engagement Survey',
            ],
            'FIN' => [
                'Annual Budget Report',
                'Financial Statement Analysis',
                'Investment Proposal',
                'Cost Reduction Plan',
                'Tax Planning Strategy',
                'Audit Report',
                'Cash Flow Projection',
                'Financial Risk Assessment',
                'Budget Variance Analysis',
                'Financial Policy Update',
            ],
            'MKT' => [
                'Marketing Campaign Plan',
                'Brand Strategy Proposal',
                'Digital Marketing Budget',
                'Market Research Report',
                'Product Launch Plan',
                'Customer Acquisition Strategy',
                'Social Media Strategy',
                'Marketing ROI Analysis',
                'Competitive Analysis',
                'Marketing Budget Allocation',
            ],
            'OPS' => [
                'Operational Efficiency Report',
                'Process Improvement Plan',
                'Supply Chain Optimization',
                'Quality Management System',
                'Operational Budget',
                'Performance Metrics Report',
                'Risk Management Plan',
                'Compliance Audit Report',
                'Operational Strategy',
                'Resource Allocation Plan',
            ],
            'SALES' => [
                'Sales Strategy Plan',
                'Revenue Projection',
                'Customer Relationship Plan',
                'Sales Training Program',
                'Sales Performance Report',
                'Market Expansion Plan',
                'Sales Budget Proposal',
                'Customer Retention Strategy',
                'Sales Process Optimization',
                'Sales Team Structure',
            ],
            'LEGAL' => [
                'Contract Review Report',
                'Compliance Policy Update',
                'Legal Risk Assessment',
                'Intellectual Property Strategy',
                'Regulatory Compliance Plan',
                'Legal Budget Proposal',
                'Contract Template Update',
                'Legal Process Optimization',
                'Compliance Training Plan',
                'Legal Strategy Document',
            ],
            'RND' => [
                'R&D Project Proposal',
                'Innovation Strategy Plan',
                'Research Methodology',
                'Product Development Roadmap',
                'Technology Assessment Report',
                'R&D Budget Allocation',
                'Patent Application Strategy',
                'Research Collaboration Plan',
                'Innovation Metrics Report',
                'R&D Process Optimization',
            ],
            'CS' => [
                'Customer Service Policy',
                'Service Quality Report',
                'Customer Feedback Analysis',
                'Service Improvement Plan',
                'Customer Support Strategy',
                'Service Level Agreement',
                'Customer Satisfaction Survey',
                'Service Process Optimization',
                'Customer Training Program',
                'Service Budget Proposal',
            ],
            'QA' => [
                'Quality Management Plan',
                'Quality Metrics Report',
                'Process Improvement Plan',
                'Quality Audit Report',
                'Quality Standards Update',
                'Quality Training Program',
                'Quality Budget Proposal',
                'Quality Risk Assessment',
                'Quality Process Optimization',
                'Quality Strategy Document',
            ],
        ];

        $totalDocumentsCreated = 0;

        foreach ($divisions as $division) {
            // Get staff users for this specific division
            $divisionUsers = User::where('role_id', $staffRole->id)
                                ->where('division_id', $division->id)
                                ->get();
            
            // Skip if no staff users in this division
            if ($divisionUsers->isEmpty()) {
                echo "Skipping documents for division {$division->name} ({$division->code}) - no staff users found\n";
                continue;
            }
            
            // Get documents for this division using code
            $documents = $divisionDocuments[$division->code] ?? [];
            
            if (empty($documents)) {
                echo "No document templates found for division {$division->name} ({$division->code})\n";
                continue;
            }
            
            $documentsCreated = 0;
            
            // Create 10 documents per division (as per template)
            foreach ($documents as $index => $title) {
                // Double check to make sure we have users
                if ($divisionUsers->isEmpty()) {
                    echo "No users available for division {$division->name} ({$division->code}) - skipping remaining documents\n";
                    break;
                }
                
                $user = $divisionUsers->random();
                $documentType = $documentTypes->random();
                
                $createdAt = $faker->dateTimeBetween('2025-01-01', '2025-12-31');
                $doc = Document::create([
                    'user_id' => $user->id,
                    'division_id' => $division->id,
                    'document_type_id' => $documentType->id,
                    'title' => $title,
                    'description' => $faker->paragraph(3),
                    'file_path' => 'documents/sample.pdf', // Placeholder file path
                    'status' => $status = ['pending', 'approved', 'rejected'][rand(0, 2)],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
                // Tambahkan approval jika status approved/rejected
                if (in_array($doc->status, ['approved', 'rejected'])) {
                    $approver = $divisionUsers->random();
                    $level = $approvalLevels->random();
                    $approvedAt = (clone $createdAt)->modify('+'.rand(1,48).' hours');
                    Approval::create([
                        'document_id' => $doc->id,
                        'user_id' => $approver->id,
                        'level_id' => $level->id,
                        'status' => $doc->status,
                        'notes' => $doc->status === 'approved' ? 'Disetujui otomatis' : 'Ditolak otomatis',
                        'approved_at' => $approvedAt,
                        'created_at' => $createdAt,
                        'updated_at' => $approvedAt,
                    ]);
                }
                
                $documentsCreated++;
            }
            
            echo "Created {$documentsCreated} documents for division {$division->name} ({$division->code})\n";
            $totalDocumentsCreated += $documentsCreated;
        }
        
        echo "Total documents created: {$totalDocumentsCreated}\n";
    }
} 