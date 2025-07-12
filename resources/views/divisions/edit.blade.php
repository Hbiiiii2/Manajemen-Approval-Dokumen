@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Edit Divisi</h6>
        </div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form action="{{ route('divisions.update', $division->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="name" class="form-label">Nama Divisi</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $division->name) }}" required>
            </div>
            <div class="mb-3">
              <label for="code" class="form-label">Kode Divisi</label>
              <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $division->code) }}" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Deskripsi</label>
              <textarea class="form-control" id="description" name="description">{{ old('description', $division->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-control" id="status" name="status" required>
                <option value="active" {{ old('status', $division->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ old('status', $division->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
              </select>
            </div>
            <a href="{{ route('divisions.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 