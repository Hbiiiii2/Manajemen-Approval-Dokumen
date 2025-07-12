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
                      @else
                        <span class="badge badge-sm bg-gradient-success">Active</span>
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
              {{ $users->links() }}
            </div>
            <div class="text-center text-muted small">
              Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }} data
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