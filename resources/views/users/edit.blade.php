@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6>Edit User</h6>
              <p class="text-sm">
                <i class="ni ni-settings-gear-65 text-info"></i>
                <span class="font-weight-bold">Edit informasi user</span>
              </p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
              <i class="ni ni-fat-remove"></i> Kembali
            </a>
          </div>
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

          <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="form-control-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email" class="form-control-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password" class="form-control-label">Password Baru (Opsional)</label>
                  <input type="password" class="form-control" id="password" name="password">
                  <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password_confirmation" class="form-control-label">Konfirmasi Password Baru</label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="role_id" class="form-control-label">Role</label>
              <select class="form-control" id="role_id" name="role_id" required>
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                    {{ ucfirst($role->name) }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
              <button type="submit" class="btn btn-primary">Update User</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 