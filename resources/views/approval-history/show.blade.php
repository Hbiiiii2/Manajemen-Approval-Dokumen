@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6>Detail Approval History</h6>
              <p class="text-sm">
                <i class="ni ni-time-alarm text-info"></i>
                <span class="font-weight-bold">Detail approval dokumen</span>
              </p>
            </div>
            <a href="{{ route('approval-history.index') }}" class="btn btn-secondary">
              <i class="ni ni-fat-remove"></i> Kembali
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6 class="mb-3">Informasi Dokumen</h6>
              <table class="table table-borderless">
                <tr>
                  <td width="30%"><strong>Judul Dokumen:</strong></td>
                  <td>{{ $approval->document->title }}</td>
                </tr>
                <tr>
                  <td><strong>Tipe Dokumen:</strong></td>
                  <td>{{ $approval->document->documentType->name }}</td>
                </tr>
                <tr>
                  <td><strong>Pembuat:</strong></td>
                  <td>{{ $approval->document->user->name }}</td>
                </tr>
                <tr>
                  <td><strong>Email Pembuat:</strong></td>
                  <td>{{ $approval->document->user->email }}</td>
                </tr>
                <tr>
                  <td><strong>Deskripsi:</strong></td>
                  <td>{{ $approval->document->description ?: '-' }}</td>
                </tr>
                <tr>
                  <td><strong>Status Dokumen:</strong></td>
                  <td>
                    @if($approval->document->status == 'pending')
                      <span class="badge badge-sm bg-gradient-warning">Pending</span>
                    @elseif($approval->document->status == 'approved')
                      <span class="badge badge-sm bg-gradient-success">Approved</span>
                    @elseif($approval->document->status == 'rejected')
                      <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td><strong>Tanggal Dibuat:</strong></td>
                  <td>{{ $approval->document->created_at->format('d/m/Y H:i') }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h6 class="mb-3">Informasi Approval</h6>
              <table class="table table-borderless">
                <tr>
                  <td width="30%"><strong>Level Approval:</strong></td>
                  <td>{{ $approval->level->name }}</td>
                </tr>
                <tr>
                  <td><strong>Status Approval:</strong></td>
                  <td>
                    @if($approval->status == 'approved')
                      <span class="badge badge-sm bg-gradient-success">Approved</span>
                    @elseif($approval->status == 'rejected')
                      <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                    @else
                      <span class="badge badge-sm bg-gradient-warning">Pending</span>
                    @endif
                  </td>
                </tr>
                <tr>
                  <td><strong>Tanggal Approval:</strong></td>
                  <td>{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}</td>
                </tr>
                <tr>
                  <td><strong>Catatan:</strong></td>
                  <td>{{ $approval->notes ?: '-' }}</td>
                </tr>
              </table>
            </div>
          </div>

          @if($approval->document->file_path)
          <div class="row mt-4">
            <div class="col-12">
              <h6 class="mb-3">File Dokumen</h6>
              <div class="card">
                <div class="card-body">
                  <p class="mb-2"><strong>File:</strong> {{ basename($approval->document->file_path) }}</p>
                  <a href="{{ Storage::url($approval->document->file_path) }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> Download File
                  </a>
                </div>
              </div>
            </div>
          </div>
          @endif

          @if($approval->document->approvals->count() > 1)
          <div class="row mt-4">
            <div class="col-12">
              <h6 class="mb-3">Riwayat Approval Dokumen Ini</h6>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Level</th>
                      <th>Approver</th>
                      <th>Status</th>
                      <th>Tanggal</th>
                      <th>Catatan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($approval->document->approvals->sortBy('created_at') as $docApproval)
                    <tr>
                      <td>{{ $docApproval->level->name }}</td>
                      <td>{{ $docApproval->user->name }}</td>
                      <td>
                        @if($docApproval->status == 'approved')
                          <span class="badge badge-sm bg-gradient-success">Approved</span>
                        @elseif($docApproval->status == 'rejected')
                          <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                        @else
                          <span class="badge badge-sm bg-gradient-warning">Pending</span>
                        @endif
                      </td>
                      <td>{{ $docApproval->approved_at ? $docApproval->approved_at->format('d/m/Y H:i') : '-' }}</td>
                      <td>{{ $docApproval->notes ?: '-' }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 