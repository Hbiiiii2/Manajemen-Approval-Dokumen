@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6>Tambah User Baru</h6>
              <p class="text-sm">
                <i class="ni ni-single-02 text-info"></i>
                <span class="font-weight-bold">Tambah user baru ke dalam sistem</span>
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

          <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="form-control-label">Nama Lengkap <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email" class="form-control-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password" class="form-control-label">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text text-muted">Minimal 8 karakter</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password_confirmation" class="form-control-label">Konfirmasi Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="primary_division_id" class="form-control-label">Divisi Utama <span class="text-danger">*</span></label>
                  <select class="form-control @error('primary_division_id') is-invalid @enderror" id="primary_division_id" name="primary_division_id" required>
                    <option value="">Pilih Divisi Utama</option>
                    @foreach($divisions as $division)
                      <option value="{{ $division->id }}" {{ old('primary_division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }} ({{ $division->code }})
                      </option>
                    @endforeach
                  </select>
                  @error('primary_division_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="primary_role_id" class="form-control-label">Role Utama <span class="text-danger">*</span></label>
                  <select class="form-control @error('primary_role_id') is-invalid @enderror" id="primary_role_id" name="primary_role_id" required>
                    <option value="">Pilih Role Utama</option>
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}" {{ old('primary_role_id') == $role->id ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                      </option>
                    @endforeach
                  </select>
                  @error('primary_role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="form-control-label">Divisi Tambahan</label>
              <div id="additional-divisions">
                <!-- Additional divisions will be added here -->
              </div>
              <button type="button" class="btn btn-info btn-sm mt-2" onclick="addDivision()">
                <i class="ni ni-fat-add"></i> Tambah Divisi
              </button>
              <small class="form-text text-muted">Opsional: User bisa memiliki role di divisi lain</small>
            </div>

            <div class="form-group">
              <label for="role_id" class="form-control-label">Role <span class="text-danger">*</span></label>
              <select class="form-control @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                <option value="">Pilih Role</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                  </option>
                @endforeach
              </select>
              @error('role_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">
                <strong>Staff:</strong> Hanya bisa membuat dokumen<br>
                <strong>Manager:</strong> Bisa membuat dan approve dokumen<br>
                <strong>Section Head:</strong> Hanya bisa approve dokumen<br>
                <strong>Admin:</strong> Akses penuh ke semua fitur
              </small>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
              <button type="submit" class="btn btn-primary">
                <i class="ni ni-single-02"></i> Tambah User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let divisionCount = 0;

function addDivision() {
  const container = document.getElementById('additional-divisions');
  const divisionDiv = document.createElement('div');
  divisionDiv.className = 'row mt-2 additional-division';
  divisionDiv.innerHTML = `
    <div class="col-md-5">
      <select class="form-control" name="divisions[${divisionCount}][division_id]" required>
        <option value="">Pilih Divisi</option>
        @foreach($divisions as $division)
          <option value="{{ $division->id }}">{{ $division->name }} ({{ $division->code }})</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-5">
      <select class="form-control" name="divisions[${divisionCount}][role_id]" required>
        <option value="">Pilih Role</option>
        @foreach($roles as $role)
          <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button type="button" class="btn btn-danger btn-sm" onclick="removeDivision(this)">
        <i class="ni ni-fat-remove"></i> Hapus
      </button>
    </div>
  `;
  container.appendChild(divisionDiv);
  divisionCount++;
}

function removeDivision(button) {
  button.closest('.additional-division').remove();
}
</script>
@endsection 