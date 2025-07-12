@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <div>
            <h6>Manajemen User</h6>
            <p class="text-sm">
              <i class="ni ni-settings-gear-65 text-info"></i>
              <span class="font-weight-bold">Kelola semua user dalam sistem</span>
            </p>
          </div>
          <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="ni ni-single-02"></i> Tambah User
          </a>
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
                  <form method="GET" action="{{ route('users.index') }}" id="searchForm">
                    <div class="row">
                      <!-- Search Input -->
                      <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Cari User</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama atau email...">
                      </div>
                      
                      <!-- Role Filter -->
                      <div class="col-md-3 mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-control" id="role_id" name="role_id">
                          <option value="">Semua Role</option>
                          @foreach($filterOptions['roles'] as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                              {{ ucfirst($role->name) }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <!-- Division Filter -->
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
                      
                      <!-- Status Filter -->
                      <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                          <option value="">Semua Status</option>
                          @foreach($filterOptions['statuses'] as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                              {{ $label }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      
                      <!-- Date Range -->
                      <div class="col-md-3 mb-3">
                        <label for="date_from" class="form-label">Tanggal Dibuat Dari</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                      </div>
                      
                      <div class="col-md-3 mb-3">
                        <label for="date_to" class="form-label">Tanggal Dibuat Sampai</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                      </div>
                      
                      <!-- Last Login Range -->
                      <div class="col-md-3 mb-3">
                        <label for="last_login_from" class="form-label">Last Login Dari</label>
                        <input type="date" class="form-control" id="last_login_from" name="last_login_from" 
                               value="{{ request('last_login_from') }}">
                      </div>
                      
                      <div class="col-md-3 mb-3">
                        <label for="last_login_to" class="form-label">Last Login Sampai</label>
                        <input type="date" class="form-control" id="last_login_to" name="last_login_to" 
                               value="{{ request('last_login_to') }}">
                      </div>
                      
                      <!-- Sort Options -->
                      <div class="col-md-3 mb-3">
                        <label for="sort_by" class="form-label">Urutkan Berdasarkan</label>
                        <select class="form-control" id="sort_by" name="sort_by">
                          @foreach($filterOptions['sortOptions'] as $value => $label)
                            <option value="{{ $value }}" {{ request('sort_by', 'created_at') == $value ? 'selected' : '' }}>
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
                              @if($users->total() > 0)
                                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} 
                                dari {{ $users->total() }} user
                              @else
                                Tidak ada user ditemukan
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

          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if($users->count() > 0)
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Dibuat</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="avatar avatar-sm bg-gradient-secondary rounded-circle me-3">
                          <i class="ni ni-single-02 text-white"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                          <p class="text-xs text-secondary mb-0">ID: {{ $user->id }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      @if($user->role)
                        <span class="badge badge-sm bg-gradient-secondary">{{ ucfirst($user->role->name) }}</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">No Role</span>
                      @endif
                    </td>
                    <td class="align-middle text-center text-sm">
                      @if($user->division)
                        <span class="badge badge-sm bg-gradient-primary">{{ $user->division->name }}</span>
                        @if($user->divisionRoles->count() > 1)
                          <br><small class="text-xs text-secondary">+{{ $user->divisionRoles->count() - 1 }} divisi lain</small>
                        @endif
                      @else
                        <span class="badge badge-sm bg-gradient-warning">No Division</span>
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      @if($user->id === auth()->id())
                        <span class="badge badge-sm bg-gradient-info">Current User</span>
                      @elseif($user->last_login_at && $user->last_login_at->diffInDays(now()) <= 30)
                        <span class="badge badge-sm bg-gradient-success">Active</span>
                        <br><small class="text-xs text-secondary">{{ $user->last_login_at->diffForHumans() }}</small>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">Inactive</span>
                        @if($user->last_login_at)
                          <br><small class="text-xs text-secondary">{{ $user->last_login_at->diffForHumans() }}</small>
                        @else
                          <br><small class="text-xs text-secondary">Never logged in</small>
                        @endif
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="btn-group" role="group">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                          <i class="ni ni-settings-gear-65"></i> Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                          <i class="ni ni-fat-remove"></i> Hapus
                        </button>
                        @endif
                      </div>
                    </td>
                  </tr>

                  <!-- Delete Modal -->
                  @if($user->id !== auth()->id())
                  <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">Konfirmasi Hapus</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                          <p class="text-danger">Tindakan ini tidak dapat dibatalkan!</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
              {{ $users->appends(request()->query())->links() }}
            </div>
          @else
            <div class="text-center py-4">
              <i class="ni ni-single-02 text-secondary" style="font-size: 3rem;"></i>
              <h6 class="mt-3">Tidak ada user</h6>
              <p class="text-sm text-secondary">Belum ada user yang terdaftar dalam sistem.</p>
              <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">
                <i class="ni ni-single-02"></i> Tambah User Pertama
              </a>
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
    window.location.href = '{{ route('users.index') }}';
}

// Auto-submit form when certain filters change
document.addEventListener('DOMContentLoaded', function() {
    const autoSubmitElements = ['role_id', 'division_id', 'status', 'sort_by', 'sort_order', 'per_page'];
    
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