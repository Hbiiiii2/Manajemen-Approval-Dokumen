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
                  <div class="mb-3">
                    <a href="{{ Storage::url($approval->document->file_path) }}" target="_blank" class="btn btn-primary btn-sm">
                      <i class="fas fa-download"></i> Download File
                    </a>
                    <button type="button" class="btn btn-info btn-sm" onclick="togglePdfViewer()">
                      <i class="fas fa-eye"></i> Lihat PDF
                    </button>
                  </div>
                  
                  <!-- PDF Viewer -->
                  <div id="pdfViewer" class="mt-3" style="display: none;">
                    <div class="card shadow-lg border-0">
                      <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                          <i class="fas fa-eye me-2"></i>
                          <h6 class="mb-0 fw-bold">Preview Dokumen</h6>
                          <span class="badge bg-light text-dark ms-2">{{ strtoupper($fileExtension ?? 'PDF') }}</span>
                        </div>
                        <button type="button" class="btn btn-light btn-sm" onclick="togglePdfViewer()" title="Tutup Preview">
                          <i class="fas fa-times"></i>
                        </button>
                      </div>
                      <div class="card-body p-0 position-relative">
                        @php
                          $fileExtension = strtolower(pathinfo($approval->document->file_path, PATHINFO_EXTENSION));
                        @endphp
                        
                        @if($fileExtension == 'pdf')
                          <!-- PDF Viewer -->
                          <div class="pdf-container">
                            <iframe src="{{ Storage::url($approval->document->file_path) }}#toolbar=1&navpanes=1&scrollbar=1" 
                                    width="100%" height="700px" 
                                    style="border: none; border-radius: 0 0 0.5rem 0.5rem;"
                                    allowfullscreen>
                              <div class="text-center p-5">
                                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                <p class="text-muted">Browser Anda tidak mendukung PDF viewer.</p>
                                <a href="{{ Storage::url($approval->document->file_path) }}" target="_blank" class="btn btn-primary">
                                  <i class="fas fa-download"></i> Download PDF
                                </a>
                              </div>
                            </iframe>
                          </div>
                        @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
                          <!-- Image Viewer -->
                          <div class="image-viewer-container">
                            <div class="text-center p-4 bg-light">
                              <div class="image-wrapper position-relative">
                                <img src="{{ Storage::url($approval->document->file_path) }}" 
                                     class="img-fluid rounded shadow-sm" 
                                     style="max-height: 600px; max-width: 100%;"
                                     alt="Preview dokumen"
                                     id="previewImage">
                                <div class="image-overlay">
                                  <button class="btn btn-light btn-sm" onclick="zoomIn()" title="Zoom In">
                                    <i class="fas fa-search-plus"></i>
                                  </button>
                                  <button class="btn btn-light btn-sm" onclick="zoomOut()" title="Zoom Out">
                                    <i class="fas fa-search-minus"></i>
                                  </button>
                                  <button class="btn btn-light btn-sm" onclick="resetZoom()" title="Reset Zoom">
                                    <i class="fas fa-undo"></i>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        @else
                          <!-- Other file types -->
                          <div class="text-center p-5 bg-light">
                            <div class="file-icon-wrapper mb-3">
                              @if(in_array($fileExtension, ['doc', 'docx']))
                                <i class="fas fa-file-word fa-4x text-primary"></i>
                              @elseif(in_array($fileExtension, ['xls', 'xlsx']))
                                <i class="fas fa-file-excel fa-4x text-success"></i>
                              @elseif(in_array($fileExtension, ['ppt', 'pptx']))
                                <i class="fas fa-file-powerpoint fa-4x text-warning"></i>
                              @else
                                <i class="fas fa-file-alt fa-4x text-muted"></i>
                              @endif
                            </div>
                            <h6 class="text-muted mb-2">Preview tidak tersedia</h6>
                            <p class="text-muted small mb-3">File tipe {{ strtoupper($fileExtension) }} tidak dapat ditampilkan dalam preview</p>
                            <a href="{{ Storage::url($approval->document->file_path) }}" target="_blank" class="btn btn-primary">
                              <i class="fas fa-download"></i> Download File
                            </a>
                          </div>
                        @endif
                      </div>
                      <div class="card-footer bg-light py-2">
                        <div class="d-flex justify-content-between align-items-center">
                          <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            File: {{ basename($approval->document->file_path) }}
                          </small>
                          <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $approval->document->created_at->format('d/m/Y H:i') }}
                          </small>
                        </div>
                      </div>
                    </div>
                  </div>
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

<style>
.pdf-container {
  position: relative;
  overflow: hidden;
}

.image-viewer-container {
  position: relative;
}

.image-wrapper {
  display: inline-block;
  position: relative;
  max-width: 100%;
}

.image-overlay {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  gap: 5px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.image-wrapper:hover .image-overlay {
  opacity: 1;
}

.image-overlay .btn {
  background: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.image-overlay .btn:hover {
  background: rgba(255, 255, 255, 1);
  transform: scale(1.05);
}

#previewImage {
  transition: transform 0.3s ease;
  cursor: zoom-in;
}

.file-icon-wrapper {
  display: inline-block;
  padding: 20px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.05);
}

.card.shadow-lg {
  box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.bg-gradient-info {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

.card-header.bg-gradient-info {
  border-bottom: none;
}

.card-footer {
  border-top: 1px solid rgba(0, 0, 0, 0.125);
}

/* Animation for viewer */
#pdfViewer {
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .image-overlay {
    position: static;
    justify-content: center;
    margin-top: 10px;
    opacity: 1;
  }
  
  .image-wrapper {
    display: block;
  }
}
</style>

<script>
let currentZoom = 1;
const zoomStep = 0.2;
const maxZoom = 3;
const minZoom = 0.5;

function togglePdfViewer() {
  const viewer = document.getElementById('pdfViewer');
  if (viewer.style.display === 'none') {
    viewer.style.display = 'block';
    // Scroll to viewer with smooth animation
    setTimeout(() => {
      viewer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 100);
  } else {
    viewer.style.display = 'none';
  }
}

function zoomIn() {
  const image = document.getElementById('previewImage');
  if (image && currentZoom < maxZoom) {
    currentZoom += zoomStep;
    image.style.transform = `scale(${currentZoom})`;
  }
}

function zoomOut() {
  const image = document.getElementById('previewImage');
  if (image && currentZoom > minZoom) {
    currentZoom -= zoomStep;
    image.style.transform = `scale(${currentZoom})`;
  }
}

function resetZoom() {
  const image = document.getElementById('previewImage');
  if (image) {
    currentZoom = 1;
    image.style.transform = 'scale(1)';
  }
}

// Add click to zoom functionality for images
document.addEventListener('DOMContentLoaded', function() {
  const image = document.getElementById('previewImage');
  if (image) {
    image.addEventListener('click', function() {
      if (currentZoom === 1) {
        zoomIn();
      } else {
        resetZoom();
      }
    });
  }
});
</script>
@endsection 