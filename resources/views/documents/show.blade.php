@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6>Detail Dokumen</h6>
              <p class="text-sm">
                <i class="fa fa-info text-info"></i>
                <span class="font-weight-bold">Role: {{ ucfirst(auth()->user()->role->name) }}</span>
              </p>
            </div>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="form-control-label">Judul Dokumen</label>
                <p class="form-control-static">{{ $document->title }}</p>
              </div>
              
              <div class="form-group">
                <label class="form-control-label">Deskripsi</label>
                <p class="form-control-static">{{ $document->description }}</p>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Tipe Dokumen</label>
                    <p class="form-control-static">{{ $document->documentType->name }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Status</label>
                    <p class="form-control-static">
                      @if($document->status == 'pending')
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      @elseif($document->status == 'approved')
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      @elseif($document->status == 'rejected')
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      @endif
                    </p>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Pembuat</label>
                    <p class="form-control-static">{{ $document->user->name }}</p>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="form-control-label">Tanggal Dibuat</label>
                    <p class="form-control-static">{{ $document->created_at->format('d/m/Y H:i') }}</p>
                  </div>
                </div>
              </div>
              
              @if($document->file_path)
              <div class="form-group">
                <label class="form-control-label">File Dokumen</label>
                <p class="form-control-static">
                  <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-info btn-sm">
                    <i class="fas fa-download"></i> Download File
                  </a>
                </p>
              </div>
              @endif
            </div>
            
            <div class="col-md-4">
              <div class="card">
                <div class="card-header">
                  <h6>Riwayat Approval</h6>
                </div>
                <div class="card-body">
                  @if($document->approvals->count() > 0)
                    @foreach($document->approvals as $approval)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div>
                        <small class="text-muted">{{ $approval->user->name }}</small>
                        <br>
                        <small class="text-muted">{{ $approval->created_at->format('d/m/Y H:i') }}</small>
                      </div>
                      <div>
                        @if($approval->status == 'approved')
                          <span class="badge badge-sm bg-gradient-success">Approved</span>
                        @elseif($approval->status == 'rejected')
                          <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                        @endif
                      </div>
                    </div>
                    @if($approval->notes)
                    <div class="mb-3">
                      <small class="text-muted">Komentar:</small>
                      <p class="mb-0">{{ $approval->notes }}</p>
                    </div>
                    @endif
                    <hr>
                    @endforeach
                  @else
                    <p class="text-muted">Belum ada approval</p>
                  @endif
                </div>
              </div>
              
              @if(in_array(auth()->user()->role->name, ['manager', 'section_head', 'dept_head']) && $document->status == 'pending')
              <div class="card mt-3">
                <div class="card-header">
                  <h6>Action Approval</h6>
                </div>
                <div class="card-body">
                  <form action="{{ route('approvals.approve', $document->id) }}" method="POST" class="mb-3">
                    @csrf
                    <div class="form-group">
                      <label for="comment">Komentar (opsional):</label>
                      <textarea class="form-control" name="comment" rows="2" placeholder="Masukkan komentar..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100">
                      <i class="fas fa-check"></i> Approve
                    </button>
                  </form>
                  
                  <form action="{{ route('approvals.reject', $document->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                      <label for="reject_comment">Alasan Reject:</label>
                      <textarea class="form-control" name="comment" rows="2" placeholder="Masukkan alasan reject..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                      <i class="fas fa-times"></i> Reject
                    </button>
                  </form>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 