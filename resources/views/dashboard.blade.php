@extends('layouts.user_type.auth')

@section('content')
<style>
.dashboard-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.dashboard-card {
  transition: all 0.3s ease;
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.dashboard-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.section-title {
  color: #344767;
  font-weight: 700;
  margin-bottom: 1.5rem;
  position: relative;
  padding-left: 1rem;
}

.section-title::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 2px;
}

.stat-card {
  background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
  border: 1px solid #e9ecef;
}

.stat-card .icon-shape {
  width: 48px;
  height: 48px;
  border-radius: 12px;
}

.chart-card {
  background: #fff;
  border: 1px solid #e9ecef;
}

.table-responsive {
  border-radius: 10px;
  overflow: hidden;
}

.table th {
  background: #f8f9fa;
  border: none;
  font-weight: 600;
  color: #344767;
}

.table td {
  border: none;
  vertical-align: middle;
}

.badge {
  font-size: 0.75rem;
  font-weight: 600;
  padding: 0.5rem 0.75rem;
  border-radius: 8px;
}

.btn-sm {
  border-radius: 8px;
  font-weight: 600;
  padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
  .dashboard-card {
    margin-bottom: 1rem;
  }
  
  .section-title {
    font-size: 1.1rem;
  }
  
  .stat-card .icon-shape {
    width: 40px;
    height: 40px;
  }
}
</style>

<div class="container-fluid py-4" id="dashboard-statistics">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card dashboard-header">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h4 class="text-white mb-1">
                @if($role == 'admin')
                  Dashboard Analytics - Admin
                @elseif($role == 'dept_head')
                  Dashboard Analytics - Dept Head
                @elseif($role == 'section_head')
                  Dashboard Analytics - Section Head
                @endif
              </h4>
              <p class="text-white opacity-8 mb-0">
                @if($role == 'admin')
                  Melihat analitik seluruh sistem
                @elseif($role == 'dept_head')
                  Melihat analitik semua divisi di departemen Anda
                @elseif($role == 'section_head')
                  Melihat analitik divisi yang Anda kelola
                @endif
              </p>
            </div>
            <div class="col-lg-4 text-end">
              @if(in_array($role, ['admin', 'dept_head', 'section_head']))
              <a href="{{ route('dashboard.export-pdf', request()->all()) }}" class="btn btn-white btn-sm" target="_blank">
                <i class="fa fa-file-pdf me-2"></i> Export PDF
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- KPI Cards Section -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-chart-bar-32 text-primary me-2"></i>
        Key Performance Indicators
      </h5>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Approval Rate</p>
                <h5 class="font-weight-bolder text-primary mb-0">
                  {{ $kpi['approval_rate'] }}%
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-md">
                <i class="ni ni-chart-bar-32 text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Rata-rata Waktu</p>
                <h5 class="font-weight-bolder text-info mb-0">
                  {{ $kpi['avg_approval_time'] < 1 ? round($kpi['avg_approval_time']*60) . ' menit' : number_format($kpi['avg_approval_time'],2) . ' jam' }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow-info text-center border-radius-md">
                <i class="ni ni-time-alarm text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Approval</p>
                <h5 class="font-weight-bolder text-success mb-0">
                  {{ $kpi['total_approval'] }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-md">
                <i class="ni ni-check-bold text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Divisi</p>
                <h5 class="font-weight-bolder text-warning mb-0">
                  {{ $kpi['total_division'] }}
                </h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow-warning text-center border-radius-md">
                <i class="ni ni-building text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-chart-pie-35 text-primary me-2"></i>
        Analisis Grafik
      </h5>
    </div>
    <div class="col-lg-4 mb-4">
      <div class="card dashboard-card chart-card h-100">
        <div class="card-header pb-0">
          <h6 class="font-weight-bold text-dark">
            <i class="ni ni-chart-bar-32 text-primary me-2"></i>
            Dokumen per Bulan
          </h6>
        </div>
        <div class="card-body">
          <canvas id="documentChart" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4 mb-4">
      <div class="card dashboard-card chart-card h-100">
        <div class="card-header pb-0">
          <h6 class="font-weight-bold text-dark">
            <i class="ni ni-building text-info me-2"></i>
            Dokumen per Divisi
          </h6>
        </div>
        <div class="card-body">
          <canvas id="divisionChart" height="200"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4 mb-4">
      <div class="card dashboard-card chart-card h-100">
        <div class="card-header pb-0">
          <h6 class="font-weight-bold text-dark">
            <i class="ni ni-time-alarm text-warning me-2"></i>
            Waktu Approval per Bulan
          </h6>
        </div>
        <div class="card-body">
          <canvas id="approvalTimeChart" height="200"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Document Statistics Cards -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-single-copy-04 text-primary me-2"></i>
        Statistik Dokumen
      </h5>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Dokumen</p>
                <h5 class="font-weight-bolder text-dark mb-0">{{ $stats['total'] }}</h5>
                <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">Role: {{ ucfirst($role) }}</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-dark shadow-dark text-center border-radius-md">
                <i class="ni ni-single-copy-04 text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Pending</p>
                <h5 class="font-weight-bolder text-warning mb-0">{{ $stats['pending'] }}</h5>
                <p class="mb-0">
                  <span class="text-warning text-sm font-weight-bolder">Menunggu Approval</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow-warning text-center border-radius-md">
                <i class="ni ni-time-alarm text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Approved</p>
                <h5 class="font-weight-bolder text-success mb-0">{{ $stats['approved'] }}</h5>
                <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">Dokumen Disetujui</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-md">
                <i class="ni ni-check-bold text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Rejected</p>
                <h5 class="font-weight-bolder text-danger mb-0">{{ $stats['rejected'] }}</h5>
                <p class="mb-0">
                  <span class="text-danger text-sm font-weight-bolder">Dokumen Ditolak</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center border-radius-md">
                <i class="ni ni-fat-remove text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if(in_array($role, ['dept_head', 'section_head']))
  <!-- Approval Statistics for Managers -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-check-bold text-primary me-2"></i>
        Statistik Approval Anda
      </h5>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">To Approve</p>
                <h5 class="font-weight-bolder text-info mb-0">{{ $stats['to_approve'] }}</h5>
                <p class="mb-0">
                  <span class="text-info text-sm font-weight-bolder">Perlu Approval</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-info shadow-info text-center border-radius-md">
                <i class="ni ni-time-alarm text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Approved</p>
                <h5 class="font-weight-bolder text-success mb-0">{{ \App\Models\Approval::where('user_id', auth()->id())->where('status', 'approved')->count() }}</h5>
                <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">Approval yang Dilakukan</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-md">
                <i class="ni ni-check-bold text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Rejected</p>
                <h5 class="font-weight-bolder text-danger mb-0">{{ \App\Models\Approval::where('user_id', auth()->id())->where('status', 'rejected')->count() }}</h5>
                <p class="mb-0">
                  <span class="text-danger text-sm font-weight-bolder">Rejection yang Dilakukan</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-danger shadow-danger text-center border-radius-md">
                <i class="ni ni-fat-remove text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card dashboard-card stat-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Approval Rate</p>
                @php
                  $totalApprovals = \App\Models\Approval::where('user_id', auth()->id())->count();
                  $approvedCount = \App\Models\Approval::where('user_id', auth()->id())->where('status', 'approved')->count();
                  $approvalRate = $totalApprovals > 0 ? round(($approvedCount / $totalApprovals) * 100, 1) : 0;
                @endphp
                <h5 class="font-weight-bolder text-primary mb-0">{{ $approvalRate }}%</h5>
                <p class="mb-0">
                  <span class="text-primary text-sm font-weight-bolder">Rate Approval Anda</span>
                </p>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-primary shadow-primary text-center border-radius-md">
                <i class="ni ni-chart-bar-32 text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Data Tables Section -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-collection text-primary me-2"></i>
        Data Dokumen
      </h5>
    </div>
    
    <!-- Dokumen Terbaru -->
    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card dashboard-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-single-copy-04 text-info me-2"></i>
                Dokumen Terbaru
              </h6>
              <p class="text-sm text-secondary mb-0">Dokumen terbaru yang dibuat</p>
            </div>
            <div class="col-auto">
              <a href="{{ route('documents.index') }}" class="btn btn-info btn-sm">
                <i class="ni ni-world-2"></i> Lihat Semua
              </a>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          @if($recentDocuments->count() > 0)
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    @if(auth()->user()->role->name == 'admin')
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Divisi</th>
                    @endif
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentDocuments as $document)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ Str::limit($document->title, 30) }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $document->user->name }}</p>
                        </div>
                      </div>
                    </td>
                    @if(auth()->user()->role->name == 'admin')
                    <td>
                      @if($document->division)
                        <span class="badge badge-sm bg-gradient-secondary">{{ $document->division->name }}</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">-</span>
                      @endif
                    </td>
                    @endif
                    <td>
                      @if($document->status == 'pending')
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      @elseif($document->status == 'approved')
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      @elseif($document->status == 'rejected')
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      @endif
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="text-secondary text-xs font-weight-bold">{{ $document->created_at->format('d/m/Y') }}</span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="ni ni-single-copy-04 text-secondary" style="font-size: 2rem;"></i>
              <p class="text-sm text-secondary mt-2">Belum ada dokumen</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    @if(in_array($role, ['dept_head', 'section_head']))
    <!-- Dokumen Pending Approval -->
    <div class="col-lg-6 col-md-6 mb-4">
      <div class="card dashboard-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-time-alarm text-warning me-2"></i>
                Pending Approval
              </h6>
              <p class="text-sm text-secondary mb-0">Dokumen yang perlu diapprove</p>
            </div>
            <div class="col-auto">
              <a href="{{ route('approval-history.index') }}" class="btn btn-warning btn-sm">
                <i class="ni ni-time-alarm"></i> History
              </a>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pb-2">
          @if($pendingApprovals->count() > 0)
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                    @if(auth()->user()->role->name == 'admin')
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Divisi</th>
                    @endif
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pendingApprovals as $document)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ Str::limit($document->title, 25) }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $document->documentType->name }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $document->user->name }}</p>
                      <p class="text-xs text-secondary mb-0">{{ $document->user->email }}</p>
                    </td>
                    @if(auth()->user()->role->name == 'admin')
                    <td>
                      @if($document->division)
                        <span class="badge badge-sm bg-gradient-secondary">{{ $document->division->name }}</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">-</span>
                      @endif
                    </td>
                    @endif
                    <td class="align-middle text-center">
                      <a href="{{ route('documents.show', $document->id) }}" class="btn btn-warning btn-sm">
                        <i class="ni ni-check-bold"></i> Review
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="ni ni-check-bold text-success" style="font-size: 2rem;"></i>
              <p class="text-sm text-secondary mt-2">Tidak ada dokumen pending</p>
            </div>
          @endif
        </div>
      </div>
    </div>
    @endif
  </div>

  @if(in_array($role, ['dept_head', 'section_head', 'admin']) && $approvalHistory->count() > 0)
  <!-- Riwayat Approval -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card dashboard-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-time-alarm text-info me-2"></i>
                Riwayat Approval
              </h6>
              <p class="text-sm text-secondary mb-0">Riwayat approval yang telah dilakukan</p>
            </div>
            <div class="col-auto">
              <a href="{{ route('approval-history.index') }}" class="btn btn-info btn-sm">
                <i class="ni ni-time-alarm"></i> Lihat Semua
              </a>
            </div>
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Komentar</th>
                </tr>
              </thead>
              <tbody>
                @foreach($approvalHistory as $approval)
                <tr>
                  <td>
                    <div class="d-flex px-2 py-1">
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ Str::limit($approval->document->title, 30) }}</h6>
                        <p class="text-xs text-secondary mb-0">{{ $approval->document->documentType->name }}</p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $approval->document->user->name }}</p>
                    <p class="text-xs text-secondary mb-0">{{ $approval->document->user->email }}</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    @if($approval->status == 'approved')
                      <span class="badge badge-sm bg-gradient-success">Approved</span>
                    @elseif($approval->status == 'rejected')
                      <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                    @endif
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ $approval->created_at->format('d/m/Y H:i') }}</span>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">{{ Str::limit($approval->notes, 20) }}</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Statistik Dokumen Chart -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card dashboard-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-chart-bar-32 text-primary me-2"></i>
                Statistik Dokumen ({{ date('Y') }})
              </h6>
              <p class="text-sm text-secondary mb-0">Grafik dokumen per bulan</p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <canvas id="documentChart2025" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

@if($chartData->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Grafik Dokumen per Bulan
  var chartData = @json($chartData);
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var dataArr = new Array(12).fill(0);
  chartData.forEach(function(item) {
    dataArr[item.month-1] = item.count;
  });
  new Chart(document.getElementById('documentChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Jumlah Dokumen',
        data: dataArr,
        borderColor: '#5e72e4',
        backgroundColor: 'rgba(94, 114, 228, 0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });

  // Dokumen per Divisi
  var chartDivision = @json($chartDivision);
  var divisionLabels = chartDivision.map(function(item){return item.division ? item.division.name : '-';});
  var divisionCounts = chartDivision.map(function(item){return item.count;});
  new Chart(document.getElementById('divisionChart').getContext('2d'), {
    type: 'bar',
    data: {
      labels: divisionLabels,
      datasets: [{
        label: 'Jumlah Dokumen',
        data: divisionCounts,
        backgroundColor: '#2dce89',
        borderColor: '#2dce89',
        borderWidth: 1
      }]
    },
    options: {responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });

  // Rata-rata Waktu Approval per Bulan
  var chartApprovalTime = @json($chartApprovalTime);
  var approvalTimeArr = new Array(12).fill(0);
  chartApprovalTime.forEach(function(item){
    approvalTimeArr[item.month-1] = item.avg_time ? (item.avg_time/3600).toFixed(2) : 0;
  });
  new Chart(document.getElementById('approvalTimeChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Rata-rata Jam',
        data: approvalTimeArr,
        borderColor: '#f5365c',
        backgroundColor: 'rgba(245,54,92,0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });

  // Statistik Dokumen (2025)
  var chartData2025 = @json($chartData);
  var months2025 = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var dataArr2025 = new Array(12).fill(0);
  chartData2025.forEach(function(item) {
    dataArr2025[item.month-1] = item.count;
  });
  new Chart(document.getElementById('documentChart2025').getContext('2d'), {
    type: 'line',
    data: {
      labels: months2025,
      datasets: [{
        label: 'Jumlah Dokumen',
        data: dataArr2025,
        borderColor: '#36a2eb',
        backgroundColor: 'rgba(54,162,235,0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
  });
});
</script>
@endif
@endsection

