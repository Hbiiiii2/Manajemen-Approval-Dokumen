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
                  <th>Nama</th>
                  <th>Kode</th>
                  <th>Status</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($divisions as $division)
                <tr>
                  <td>{{ $division->name }}</td>
                  <td>{{ $division->code }}</td>
                  <td>
                    @if($division->status == 'active')
                      <span class="badge bg-success">Aktif</span>
                    @else
                      <span class="badge bg-danger">Nonaktif</span>
                    @endif
                  </td>
                  <td>{{ $division->description }}</td>
                  <td>
                    <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus divisi?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-center mt-3">
            {{ $divisions->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 