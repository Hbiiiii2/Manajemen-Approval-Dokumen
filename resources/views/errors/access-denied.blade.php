@extends('layouts.user_type.auth')

@section('content')
<style>
.error-container {
  min-height: 70vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.error-card {
  background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
  border-radius: 20px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
  border: none;
  max-width: 500px;
  width: 100%;
}

.error-icon {
  width: 120px;
  height: 120px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 2rem;
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.error-icon i {
  font-size: 3rem;
  color: white;
}

.btn-action {
  border-radius: 12px;
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  transition: all 0.3s ease;
}

.btn-action:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.feature-list {
  background: #f8f9fa;
  border-radius: 12px;
  padding: 1.5rem;
  margin-top: 1.5rem;
}

.feature-item {
  display: flex;
  align-items: center;
  margin-bottom: 0.75rem;
  color: #6c757d;
}

.feature-item i {
  color: #28a745;
  margin-right: 0.75rem;
  font-size: 1.1rem;
}
</style>

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="error-container">
        <div class="card error-card">
          <div class="card-body text-center p-5">
            <div class="error-icon">
              <i class="ni ni-lock-circle"></i>
            </div>
            
            <h3 class="text-dark font-weight-bold mb-3">
              Akses Dibatasi
            </h3>
            
            <p class="text-muted mb-4">
              Maaf, Anda tidak memiliki akses ke dashboard analitik. 
              Dashboard ini hanya tersedia untuk Section Head dan Dept Head.
            </p>
            
            <div class="feature-list text-start">
              <h6 class="font-weight-bold text-dark mb-3">
                <i class="ni ni-single-02 text-primary me-2"></i>
                Fitur yang Tersedia untuk Staff:
              </h6>
              
              <div class="feature-item">
                <i class="ni ni-check-bold"></i>
                <span>Membuat dan mengelola dokumen pribadi</span>
              </div>
              
              <div class="feature-item">
                <i class="ni ni-check-bold"></i>
                <span>Melihat status approval dokumen</span>
              </div>
              
              <div class="feature-item">
                <i class="ni ni-check-bold"></i>
                <span>Melihat riwayat approval dokumen</span>
              </div>
              
              <div class="feature-item">
                <i class="ni ni-check-bold"></i>
                <span>Dashboard personal dengan statistik pribadi</span>
              </div>
              
              <div class="feature-item">
                <i class="ni ni-check-bold"></i>
                <span>Mengelola profil dan informasi pribadi</span>
              </div>
            </div>
            
            <div class="row mt-4">
              <div class="col-md-6 mb-3">
                <a href="{{ route('dashboard-staff') }}" class="btn btn-primary btn-action w-100">
                  <i class="ni ni-single-02 me-2"></i>
                  Dashboard Staff
                </a>
              </div>
              <div class="col-md-6 mb-3">
                <a href="{{ route('documents.create') }}" class="btn btn-success btn-action w-100">
                  <i class="ni ni-fat-add me-2"></i>
                  Buat Dokumen
                </a>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-6 mb-3">
                <a href="{{ route('documents.index') }}" class="btn btn-info btn-action w-100">
                  <i class="ni ni-single-copy-04 me-2"></i>
                  Dokumen Saya
                </a>
              </div>
              <div class="col-md-6 mb-3">
                <a href="{{ route('profile') }}" class="btn btn-secondary btn-action w-100">
                  <i class="ni ni-settings-gear-65 me-2"></i>
                  Profil
                </a>
              </div>
            </div>
            
            <hr class="my-4">
            
            <p class="text-sm text-muted mb-0">
              <i class="ni ni-info text-info me-1"></i>
              Jika Anda memerlukan akses ke dashboard analitik, 
              silakan hubungi administrator sistem.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 