
@extends('layouts.user_type.auth')

@section('content')
<div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
  <div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
      <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
      <div class="row gx-4">
        <div class="col-auto">
          <div class="avatar avatar-xl position-relative">
            @if($user->profile_photo)
              <img src="{{ Storage::url($user->profile_photo) }}" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            @else
              <img src="../assets/img/bruce-mars.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
            @endif
          </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">
              {{ $user->name }}
            </h5>
            <p class="mb-0 font-weight-bold text-sm">
              {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="container-fluid py-4">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="row">
      <!-- Profile Information -->
      <div class="col-12 col-xl-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-md-8 d-flex align-items-center">
                <h6 class="mb-0">Informasi Profile</h6>
              </div>
              <div class="col-md-4 text-end">
                <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                  <i class="fas fa-user-edit text-secondary text-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Profile"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <ul class="list-group">
              <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                <strong class="text-dark">Nama Lengkap:</strong> &nbsp; {{ $user->name }}
              </li>
              <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Email:</strong> &nbsp; {{ $user->email }}
              </li>
              <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Role:</strong> &nbsp; {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
              </li>
              <li class="list-group-item border-0 ps-0 text-sm">
                <strong class="text-dark">Bergabung Sejak:</strong> &nbsp; {{ $user->created_at->format('d/m/Y H:i') }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Change Password -->
      <div class="col-12 col-xl-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Ganti Password</h6>
          </div>
          <div class="card-body p-3">
            <form action="{{ route('profile.update-password') }}" method="POST">
              @csrf
              <div class="form-group">
                <label for="current_password" class="form-control-label">Password Saat Ini <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <div class="form-group">
                <label for="new_password" class="form-control-label">Password Baru <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                @error('new_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Minimal 8 karakter</small>
              </div>
              
              <div class="form-group">
                <label for="new_password_confirmation" class="form-control-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
              </div>
              
              <button type="submit" class="btn btn-primary">
                <i class="ni ni-lock-circle-open"></i> Ganti Password
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Upload Profile Photo -->
      <div class="col-12 col-xl-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Foto Profile</h6>
          </div>
          <div class="card-body p-3">
            <div class="text-center mb-3">
              @if($user->profile_photo)
                <img src="{{ Storage::url($user->profile_photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
              @else
                <img src="../assets/img/bruce-mars.jpg" alt="Default Profile" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
              @endif
            </div>
            
            <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="profile_photo" class="form-control-label">Upload Foto Profile</label>
                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*">
                @error('profile_photo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
              </div>
              
              <button type="submit" class="btn btn-success">
                <i class="ni ni-image"></i> Upload Foto
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="name" class="form-control-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="email" class="form-control-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          
          <div class="form-group">
            <label for="profile_photo" class="form-control-label">Foto Profile</label>
            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*">
            @error('profile_photo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

