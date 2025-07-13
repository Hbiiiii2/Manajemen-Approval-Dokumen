@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h6>Daftar Divisi</h6>
          <a href="{{ route('divisions.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Divisi</a>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Divisi</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Department</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah User</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($divisions as $division)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $division->name }}</h6>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs text-secondary mb-0">{{ $division->department->name ?? '-' }}</p>
                  </td>
                  <td>
                    <p class="text-xs text-secondary mb-0">{{ $division->description ?? '-' }}</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <span class="badge badge-sm bg-gradient-success">{{ $division->users->count() }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus divisi ini?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $divisions->links() }}
          </div>
          <div class="text-center text-muted small">
            Menampilkan {{ $divisions->firstItem() }} sampai {{ $divisions->lastItem() }} dari {{ $divisions->total() }} data
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 