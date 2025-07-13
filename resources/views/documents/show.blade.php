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
              
              <!-- Multi-file display section -->
              @if($document->files->count() > 0)
              <div class="form-group">
                <label class="form-control-label">File Dokumen</label>
                <div class="table-responsive">
                  <table class="table table-bordered table-hover align-middle shadow-sm">
                    <thead class="table-light">
                      <tr>
                        <th class="text-center">Versi</th>
                        <th>Nama File</th>
                        <th>Ukuran</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($document->files->sortByDesc('version') as $file)
                      <tr>
                        <td class="text-center">
                          <span class="badge bg-gradient-info px-3 py-2 fs-6">v{{ $file->version }}</span>
                        </td>
                        <td>{{ $file->original_name }}</td>
                        <td>{{ $file->file_size_formatted }}</td>
                        <td>
                          @if($file->description)
                            {{ $file->description }}
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                        <td class="text-center">
                          @if($file->status == 'active')
                            <span class="badge bg-success bg-opacity-75 px-3 py-2">ACTIVE</span>
                          @else
                            <span class="badge bg-secondary bg-opacity-50 px-3 py-2">ARCHIVED</span>
                          @endif
                        </td>
                        <td class="text-center">
                          <a href="{{ route('documents.files.edit', $file->id) }}" class="btn btn-yellow btn-sm me-1" title="Edit">
                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                          </a>
                          <a href="{{ route('documents.download', $file->id) }}" class="btn btn-cyan btn-sm me-1" title="Download">
                            <i class="fas fa-download"></i> <span class="d-none d-md-inline">Download</span>
                          </a>
                          @if($file->status == 'active')
                          <button type="button" class="btn btn-cyan btn-sm" title="Preview" onclick="previewFile('{{ $file->id }}', '{{ $file->file_extension }}', '{{ asset('storage/' . $file->file_path) }}')">
                            <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Preview</span>
                          </button>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
                @if($document->user_id == auth()->id() || auth()->user()->role->name == 'admin')
                <div class="mt-3">
                  <button type="button" class="btn btn-cyan btn-sm" data-bs-toggle="modal" data-bs-target="#uploadVersionModal">
                    <i class="fas fa-upload"></i> Upload Versi Baru
                  </button>
                </div>
                @endif
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
<div class="row mt-4">
  <div class="col-12">
    <div class="card mb-4 shadow-sm no-hover">
      <div class="card-header bg-white pb-0">
        <h6 class="mb-0">Approval Dokumen</h6>
      </div>
      <div class="card-body">
        <form action="{{ route('approvals.approve', $document->id) }}" method="POST" class="mb-3">
          @csrf
          <div class="form-group mb-2">
            <label for="comment">Komentar (opsional):</label>
            <textarea class="form-control" name="comment" rows="2" placeholder="Masukkan komentar..."></textarea>
          </div>
          <button type="submit" class="btn btn-cyan me-2">
            <i class="fas fa-check"></i> Approve
          </button>
        </form>
        <form action="{{ route('approvals.reject', $document->id) }}" method="POST">
          @csrf
          <div class="form-group mb-2">
            <label for="reject_comment">Alasan Reject:</label>
            <textarea class="form-control" name="comment" rows="2" placeholder="Masukkan alasan reject..." required></textarea>
          </div>
          <button type="submit" class="btn btn-yellow">
            <i class="fas fa-times"></i> Reject
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Comments Section -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h6>Komentar & Diskusi</h6>
        </div>
        <div class="card-body">
          <!-- Add Comment Form -->
          <form action="{{ route('documents.comments.add', $document->id) }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <textarea class="form-control" name="comment" rows="3" placeholder="Tulis komentar Anda..." required></textarea>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control" name="type" required>
                    <option value="">Pilih Tipe</option>
                    <option value="general">Umum</option>
                    <option value="approval">Approval</option>
                    <option value="revision">Revisi</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm w-100">
                  <i class="fas fa-paper-plane"></i> Kirim Komentar
                </button>
              </div>
            </div>
          </form>

          <!-- Comments List -->
          <div class="comments-container">
            @if($document->topLevelComments->count() > 0)
              @foreach($document->topLevelComments as $comment)
              <div class="comment-item mb-3">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <div class="avatar-sm bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center">
                      <span class="text-white fw-bold">{{ substr($comment->user->name, 0, 1) }}</span>
                    </div>
                  </div>
                  <div class="flex-grow-1 ms-3">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <h6 class="mb-1">{{ $comment->user->name }}</h6>
                        <small class="text-muted">{{ $comment->time_ago }}</small>
                        <span class="badge badge-sm bg-gradient-{{ $comment->type == 'approval' ? 'success' : ($comment->type == 'revision' ? 'warning' : 'info') }} ms-2">
                          {{ ucfirst($comment->type) }}
                        </span>
                      </div>
                    </div>
                    <p class="mb-2">{{ $comment->comment }}</p>
                    
                    <!-- Reply button -->
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showReplyForm({{ $comment->id }})">
                      <i class="fas fa-reply"></i> Balas
                    </button>
                    
                    <!-- Reply form (hidden by default) -->
                    <div id="reply-form-{{ $comment->id }}" class="mt-2" style="display: none;">
                      <form action="{{ route('documents.comments.add', $document->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <input type="hidden" name="type" value="general">
                        <div class="input-group">
                          <textarea class="form-control" name="comment" rows="2" placeholder="Tulis balasan..." required></textarea>
                          <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                        </div>
                      </form>
                    </div>
                    
                    <!-- Replies -->
                    @if($comment->replies->count() > 0)
                      <div class="replies mt-2">
                        @foreach($comment->replies as $reply)
                        <div class="reply-item ms-4 mt-2 p-2 bg-light rounded">
                          <div class="d-flex justify-content-between align-items-start">
                            <div>
                              <h6 class="mb-1 small">{{ $reply->user->name }}</h6>
                              <small class="text-muted">{{ $reply->time_ago }}</small>
                            </div>
                          </div>
                          <p class="mb-0 small">{{ $reply->comment }}</p>
                        </div>
                        @endforeach
                      </div>
                    @endif
                  </div>
                </div>
              </div>
              @endforeach
            @else
              <p class="text-muted text-center">Belum ada komentar</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Upload Version Modal -->
<div class="modal fade" id="uploadVersionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Upload Versi Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('documents.upload-version', $document->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>File Baru</label>
            <input type="file" class="form-control" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
          </div>
          <div class="form-group">
            <label>Deskripsi (opsional)</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Jelaskan perubahan pada versi ini..."></textarea>
          </div>
          <div class="alert alert-info">
            <small>
              <i class="fas fa-info-circle"></i>
              Versi baru akan menjadi v{{ $document->next_version }}
            </small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Upload Versi</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Preview File</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="filePreviewContent">
        <!-- Content will be loaded here -->
      </div>
    </div>
  </div>
</div>

<style>
.comment-item {
  border-left: 3px solid #e9ecef;
  padding-left: 15px;
}

.reply-item {
  border-left: 2px solid #dee2e6;
}

.avatar-sm {
  width: 40px;
  height: 40px;
  font-size: 16px;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-success {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-gradient-info {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}

.table thead th {
  font-weight: 600;
  background: #f8f9fa;
  border-bottom: 2px solid #e9ecef;
}
.table td, .table th {
  vertical-align: middle !important;
}
.badge.bg-gradient-info {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
  color: #fff;
  font-weight: 600;
  font-size: 1rem;
}
.btn-success {
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%) !important;
  border: none;
  color: #fff;
}
.btn-info {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
  border: none;
  color: #fff;
}
.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  border: none;
  color: #fff;
}
.btn:hover, .btn:focus {
  opacity: 0.9;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.btn-cyan {
  background: #16c7f9 !important;
  color: #fff !important;
  font-weight: bold;
  border: none;
  border-radius: 16px;
  padding: 8px 24px;
  box-shadow: 0 2px 8px rgba(22,199,249,0.08);
  transition: 0.2s;
}
.btn-cyan:hover, .btn-cyan:focus {
  background: #0bb3e3 !important;
  color: #fff !important;
  opacity: 0.95;
}
.btn-yellow {
  background: #ffd23b !important;
  color: #fff !important;
  font-weight: bold;
  border: none;
  border-radius: 16px;
  padding: 8px 24px;
  box-shadow: 0 2px 8px rgba(255,210,59,0.08);
  transition: 0.2s;
}
.btn-yellow:hover, .btn-yellow:focus {
  background: #ffc107 !important;
  color: #fff !important;
  opacity: 0.95;
}
@media (max-width: 768px) {
  .table-responsive {
    overflow-x: auto;
  }
}
</style>

<script>
function showReplyForm(commentId) {
  const replyForm = document.getElementById(`reply-form-${commentId}`);
  if (replyForm.style.display === 'none') {
    replyForm.style.display = 'block';
  } else {
    replyForm.style.display = 'none';
  }
}

function previewFile(fileId, extension, fileUrl) {
  const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
  const content = document.getElementById('filePreviewContent');
  
  // Show loading
  content.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>';
  modal.show();
  
  // Load content based on file type
  if (extension.toLowerCase() === 'pdf') {
    content.innerHTML = `<iframe src="${fileUrl}" width="100%" height="600px" style="border: none;"></iframe>`;
  } else if (['jpg', 'jpeg', 'png', 'gif'].includes(extension.toLowerCase())) {
    content.innerHTML = `<img src="${fileUrl}" class="img-fluid" alt="Preview">`;
  } else {
    content.innerHTML = `
      <div class="text-center p-5">
        <i class="fas fa-file fa-4x text-muted mb-3"></i>
        <p class="text-muted">Preview tidak tersedia untuk file tipe ${extension.toUpperCase()}</p>
        <a href="${fileUrl}" class="btn btn-primary" target="_blank">
          <i class="fas fa-download"></i> Download File
        </a>
      </div>
    `;
  }
}
</script>
@endsection 