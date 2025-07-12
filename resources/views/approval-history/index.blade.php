@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>History Approval Dokumen</h6>
          <p class="text-sm">
            <i class="ni ni-time-alarm text-info"></i>
            <span class="font-weight-bold">Riwayat dokumen yang pernah diapprove</span>
          </p>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <!-- Advanced Search & Filter -->
          <div class="row mb-4">
            <div class="col-12">
              <div class="card">
                <div class="card-header bg-light">
                  <h6 class="mb-0">
                    <i class="fas fa-search me-2"></i>
                    Advanced Search & Filter
                  </h6>
                </div>
                <div class="card-body">
                  <form method="GET" action="{{ route('approval-history.index') }}" id="searchForm">
                    <div class="row">
                      <!-- Search Input -->
                      <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Cari Dokumen</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan judul dokumen, deskripsi, atau nama pembuat...">
                      </div>
                      
                      <!-- Status Filter -->
                      <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status Approval</label>
                        <select class="form-control" id="status" name="status">
                          <option value="">Semua Status</option>
                          @foreach($filterOptions['statuses'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                              {{ $label }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <!-- Document Type Filter -->
                      <div class="col-md-3 mb-3">
                        <label for="document_type_id" class="form-label">Tipe Dokumen</label>
                        <select class="form-control" id="document_type_id" name="document_type_id">
                          <option value="">Semua Tipe</option>
                          @foreach($filterOptions['documentTypes'] as $type)
                            <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                              {{ $type->name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <!-- Division Filter (Admin only) -->
                      @if(auth()->user()->role->name == 'admin' && isset($filterOptions['divisions']))
                      <div class="col-md-3 mb-3">
                        <label for="division_id" class="form-label">Divisi</label>
                        <select class="form-control" id="division_id" name="division_id">
                          <option value="">Semua Divisi</option>
                          @foreach($filterOptions['divisions'] as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                              {{ $division->name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      @endif
                      
                      <!-- Approval Level Filter -->
                      <div class="col-md-3 mb-3">
                        <label for="level_id" class="form-label">Level Approval</label>
                        <select class="form-control" id="level_id" name="level_id">
                          <option value="">Semua Level</option>
                          @foreach($filterOptions['levels'] as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                              {{ $level->name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <!-- Date Range -->
                      <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">Tanggal Dari</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                      </div>
                      
                      <div class="col-md-3 mb-3">
                        <label for="date_to" class="form-label">Tanggal Sampai</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                      </div>
                      
                      <!-- Sort Options -->
                      <div class="col-md-3 mb-3">
                        <label for="sort_by" class="form-label">Urutkan Berdasarkan</label>
                        <select class="form-control" id="sort_by" name="sort_by">
                          @foreach($filterOptions['sortOptions'] as $value => $label)
                            <option value="{{ $value }}" {{ request('sort_by', 'approved_at') == $value ? 'selected' : '' }}>
                              {{ $label }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="col-md-3 mb-3">
                        <label for="sort_order" class="form-label">Urutan</label>
                        <select class="form-control" id="sort_order" name="sort_order">
                          <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                          <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                      </div>
                      
                      <!-- Per Page -->
                      <div class="col-md-3 mb-3">
                        <label for="per_page" class="form-label">Data per Halaman</label>
                        <select class="form-control" id="per_page" name="per_page">
                          <option value="5" {{ request('per_page', '15') == '5' ? 'selected' : '' }}>5</option>
                          <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                          <option value="15" {{ request('per_page', '15') == '15' ? 'selected' : '' }}>15</option>
                          <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                          <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                          <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                        </select>
                      </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                      <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <button type="submit" class="btn btn-primary me-2">
                              <i class="fas fa-search me-1"></i> Cari
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="clearFilters()">
                              <i class="fas fa-times me-1"></i> Reset
                            </button>
                          </div>
                          <div>
                            <span class="text-muted small">
                              @if($approvalHistory->total() > 0)
                                Menampilkan {{ $approvalHistory->firstItem() }} - {{ $approvalHistory->lastItem() }} 
                                dari {{ $approvalHistory->total() }} approval
                              @else
                                Tidak ada approval ditemukan
                              @endif
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Statistik -->
          <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
              <div class="card">
                <div class="card-header p-3 pt-2">
                  <div class="icon icon-lg icon-shape bg-gradient-success shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="ni ni-check-bold opacity-10"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Approved</p>
                    <h4 class="mb-0">{{ $stats['total_approved'] }}</h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
              <div class="card">
                <div class="card-header p-3 pt-2">
                  <div class="icon icon-lg icon-shape bg-gradient-danger shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="ni ni-fat-remove opacity-10"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Rejected</p>
                    <h4 class="mb-0">{{ $stats['total_rejected'] }}</h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
              <div class="card">
                <div class="card-header p-3 pt-2">
                  <div class="icon icon-lg icon-shape bg-gradient-warning shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="ni ni-time-alarm opacity-10"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Pending</p>
                    <h4 class="mb-0">{{ $stats['total_pending'] }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          @if($approvalHistory->count() > 0)
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Level</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($approvalHistory as $approval)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ Str::limit($approval->document->title, 30) }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $approval->document->documentType->name }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $approval->document->user->name }}</p>
                      <p class="text-xs text-secondary mb-0">{{ $approval->document->user->email }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-secondary">{{ $approval->level->name }}</span>
                    </td>
                    <td class="align-middle text-center">
                      @if($approval->status == 'approved')
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      @elseif($approval->status == 'rejected')
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="{{ route('approval-history.show', $approval->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
              {{ $approvalHistory->appends(request()->query())->links() }}
            </div>
          @else
            <div class="text-center py-4">
              <i class="ni ni-check-bold text-secondary" style="font-size: 3rem;"></i>
              <h6 class="mt-3">Tidak ada history approval</h6>
              <p class="text-sm text-secondary">Belum ada dokumen yang pernah diapprove.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<script>
function clearFilters() {
    // Clear all form inputs
    document.getElementById('searchForm').reset();
    
    // Redirect to clean URL
    window.location.href = '{{ route('approval-history.index') }}';
}

// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitElements = ['status', 'document_type_id', 'division_id', 'level_id', 'sort_by', 'sort_order', 'per_page'];
    
    autoSubmitElements.forEach(function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        }
    });
    
    // Add search debounce
    let searchTimeout;
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                document.getElementById('searchForm').submit();
            }, 500); // Submit after 500ms of no typing
        });
    }
});
</script>

<style>
.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 0.5rem;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-md-3, .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
}
</style> 