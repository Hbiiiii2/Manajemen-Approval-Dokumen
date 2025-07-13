@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Daftar Dokumen</h6>
                            <p class="text-sm">
                                <i class="fa fa-info text-info"></i>
                                <span class="font-weight-bold">Role: {{ ucfirst(auth()->user()->role->name) }}</span>
                            </p>
                        </div>
                        @if (in_array(auth()->user()->role->name, ['staff', 'section_head', 'admin']))
                            <a href="{{ route('documents.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Dokumen
                            </a>
                        @endif
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
                                        <form method="GET" action="{{ route('documents.index') }}" id="searchForm">
                                            <div class="row">
                                                <!-- Search Input -->
                                                <div class="col-md-6 mb-3">
                                                    <label for="search" class="form-label">Cari Dokumen</label>
                                                    <input type="text" class="form-control" id="search" name="search" 
                                                           value="{{ request('search') }}" 
                                                           placeholder="Cari berdasarkan judul, deskripsi, atau nama pembuat...">
                                                </div>
                                                
                                                <!-- Status Filter -->
                                                <div class="col-md-3 mb-3">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="">Semua Status</option>
                                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </div>
                                                
                                                <!-- Document Type Filter -->
                                                <div class="col-md-3 mb-3">
                                                    <label for="document_type_id" class="form-label">Tipe Dokumen</label>
                                                    <select class="form-control" id="document_type_id" name="document_type_id">
                                                        <option value="">Semua Tipe</option>
                                                        @foreach($options['documentTypes'] ?? [] as $type)
                                                            <option value="{{ $type->id }}" {{ request('document_type_id') == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Division Filter (Admin only) -->
                                                @if(auth()->user()->role->name === 'admin')
                                                <div class="col-md-3 mb-3">
                                                    <label for="division_id" class="form-label">Divisi</label>
                                                    <select class="form-control" id="division_id" name="division_id">
                                                        <option value="">Semua Divisi</option>
                                                        @foreach($options['divisions'] ?? [] as $division)
                                                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                                                {{ $division->name }} @if($division->department) - {{ $division->department->name }} @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                
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
                                                        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Dibuat</option>
                                                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Judul</option>
                                                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
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
                                                        <option value="5" {{ request('per_page', '10') == '5' ? 'selected' : '' }}>5</option>
                                                        <option value="10" {{ request('per_page', '10') == '10' ? 'selected' : '' }}>10</option>
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
                                                                @if($documents->total() > 0)
                                                                    Menampilkan {{ $documents->firstItem() }} - {{ $documents->lastItem() }} 
                                                                    dari {{ $documents->total() }} dokumen
                                                                @else
                                                                    Tidak ada dokumen ditemukan
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
                        @if ($documents->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Dokumen</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Pembuat</th>
                                            @if (auth()->user()->role->name == 'admin')
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Divisi</th>
                                            @endif
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tipe</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Status</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Tanggal</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($documents as $document)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $document->title }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ Str::limit($document->description, 50) }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $document->user->name }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $document->user->email }}</p>
                                                </td>
                                                @if (auth()->user()->role->name == 'admin')
                                                    <td class="align-middle text-center text-sm">
                                                        @if ($document->division)
                                                            <span
                                                                class="badge badge-sm bg-gradient-secondary">{{ $document->division->name }}</span>
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-warning">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="badge badge-sm bg-gradient-secondary">{{ $document->documentType->name }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @if ($document->status == 'pending')
                                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                                    @elseif($document->status == 'approved')
                                                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                                                    @elseif($document->status == 'rejected')
                                                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-center">
                                                    <span
                                                        class="text-secondary text-xs font-weight-bold">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <a href="{{ route('documents.show', $document->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    @if (auth()->user()->id == $document->user_id && $document->status == 'pending')
                                                        <a href="{{ route('documents.edit', $document->id) }}"
                                                            class="btn btn-warning btn-sm">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $documents->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-file-alt text-secondary" style="font-size: 3rem;"></i>
                                <h6 class="mt-3">Tidak ada dokumen</h6>
                                <p class="text-sm text-secondary">Belum ada dokumen yang dibuat.</p>
                                @if (in_array(auth()->user()->role->name, ['staff', 'section_head']))
                                    <a href="{{ route('documents.create') }}" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Buat Dokumen Pertama
                                    </a>
                                @endif
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
    window.location.href = '{{ route('documents.index') }}';
}

// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitElements = ['status', 'document_type_id', 'division_id', 'sort_by', 'sort_order', 'per_page'];
    
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

// Show/hide advanced filters
function toggleAdvancedFilters() {
    const advancedFilters = document.querySelector('.advanced-filters');
    if (advancedFilters) {
        advancedFilters.classList.toggle('d-none');
    }
}
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
