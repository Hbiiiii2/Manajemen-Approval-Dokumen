@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="mb-0">
            <i class="fas fa-lock text-warning me-2"></i>
            Akses Terbatas
          </h4>
        </div>
        <div class="card-body text-center">
          <div class="mb-4">
            <i class="fas fa-chart-line text-muted" style="font-size: 4rem;"></i>
          </div>
          
          <h5 class="text-muted mb-3">Dashboard Analitik</h5>
          <p class="text-muted mb-4">
            Maaf, Anda tidak memiliki akses ke dashboard analitik. 
            Dashboard ini hanya tersedia untuk Dept Head dan Section Head.
          </p>
          
          <div class="alert alert-info">
            <h6 class="alert-heading">Akses yang Tersedia:</h6>
            <ul class="mb-0 text-start">
              <li><strong>Dept Head:</strong> Melihat analitik semua divisi di departemennya</li>
              <li><strong>Section Head:</strong> Melihat analitik divisi yang dikelolanya</li>
              <li><strong>Staff:</strong> Hanya bisa melihat dokumen yang dibuat sendiri</li>
            </ul>
          </div>
          
          <div class="mt-4">
            <a href="{{ route('documents.index') }}" class="btn btn-primary me-2">
              <i class="fas fa-file-alt me-1"></i>
              Dokumen Saya
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
              <i class="fas fa-home me-1"></i>
              Beranda
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 