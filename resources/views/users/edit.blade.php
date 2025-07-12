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

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="primary_division_id" class="form-control-label">Divisi Utama <span class="text-danger">*</span></label>
                  <select class="form-control @error('primary_division_id') is-invalid @enderror" id="primary_division_id" name="primary_division_id" required>
                    <option value="">Pilih Divisi Utama</option>
                    @foreach($divisions as $division)
                      <option value="{{ $division->id }}" {{ old('primary_division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
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
                      <option value="{{ $role->id }}" {{ old('primary_role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
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
                @foreach($user->divisionRoles->where('is_primary', false) as $index => $divisionRole)
                <div class="row mt-2 additional-division">
                  <div class="col-md-5">
                    <select class="form-control" name="divisions[{{ $index }}][division_id]" required>
                      <option value="">Pilih Divisi</option>
                      @foreach($divisions as $division)
                        <option value="{{ $division->id }}" {{ $divisionRole->division_id == $division->id ? 'selected' : '' }}>
                          {{ $division->name }} ({{ $division->code }})
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-5">
                    <select class="form-control" name="divisions[{{ $index }}][role_id]" required>
                      <option value="">Pilih Role</option>
                      @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $divisionRole->role_id == $role->id ? 'selected' : '' }}>
                          {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeDivision(this)">
                      <i class="ni ni-fat-remove"></i> Hapus
                    </button>
                  </div>
                </div>
                @endforeach
              </div>
              <button type="button" class="btn btn-info btn-sm mt-2" onclick="addDivision()">
                <i class="ni ni-fat-add"></i> Tambah Divisi
              </button>
              <small class="form-text text-muted">Opsional: User bisa memiliki role di divisi lain</small>
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

<script>
let divisionCount = {{ $user->divisionRoles->where('is_primary', false)->count() }};

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