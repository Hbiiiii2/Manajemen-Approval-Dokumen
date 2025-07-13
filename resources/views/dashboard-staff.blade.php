@extends('layouts.user_type.auth')

@section('content')
<style>
.staff-dashboard {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.staff-card {
  transition: all 0.3s ease;
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.staff-card:hover {
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

.quick-action-btn {
  height: 120px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 15px;
  transition: all 0.3s ease;
  text-decoration: none;
  color: #344767;
  font-weight: 600;
}

.quick-action-btn:hover {
  transform: translateY(-5px);
  text-decoration: none;
  color: #344767;
}

.quick-action-btn i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.chart-container {
  background: #fff;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
  min-height: 500px;
}

.chart-container canvas {
  max-height: 400px !important;
}

@media (max-width: 768px) {
  .quick-action-btn {
    height: 100px;
    margin-bottom: 1rem;
  }
  
  .stat-number {
    font-size: 1.5rem;
  }
  
  .chart-container {
    min-height: 350px;
  }
  
  .chart-container canvas {
    max-height: 300px !important;
  }
}
</style>

<div class="container-fluid py-4">
  <!-- Header Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card staff-dashboard">
        <div class="card-body p-4">
          <div class="row align-items-center">
            <div class="col-lg-8">
              <h4 class="text-white mb-1">
                <i class="ni ni-single-02 me-2"></i>
                Dashboard Staff
              </h4>
              <p class="text-white opacity-8 mb-0">
                Selamat datang! Lihat dokumen dan aktivitas Anda
              </p>
            </div>
            <div class="col-lg-4 text-end">
              <a href="{{ route('documents.create') }}" class="btn btn-white btn-sm">
                <i class="ni ni-fat-add me-2"></i> Buat Dokumen
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-settings-gear-65 text-primary me-2"></i>
        Akses Cepat
      </h5>
    </div>
    <div class="col-md-3 mb-3">
      <a href="{{ route('documents.create') }}" class="quick-action-btn staff-card">
        <i class="ni ni-fat-add text-primary"></i>
        <span>Buat Dokumen</span>
      </a>
    </div>
    <div class="col-md-3 mb-3">
      <a href="{{ route('documents.index') }}" class="quick-action-btn staff-card">
        <i class="ni ni-single-copy-04 text-info"></i>
        <span>Dokumen Saya</span>
      </a>
    </div>
    <div class="col-md-3 mb-3">
      <a href="{{ route('profile') }}" class="quick-action-btn staff-card">
        <i class="ni ni-single-02 text-success"></i>
        <span>Profil</span>
      </a>
    </div>
    <!-- Card ke dashboard analytic dihapus -->
  </div>

  <!-- Personal Statistics -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-chart-bar-32 text-primary me-2"></i>
        Statistik Dokumen Anda
      </h5>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Dokumen</p>
                <h5 class="font-weight-bolder text-dark mb-0">{{ $personalStats['total'] }}</h5>
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
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Pending</p>
                <h5 class="font-weight-bolder text-warning mb-0">{{ $personalStats['pending'] }}</h5>
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
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Approved</p>
                <h5 class="font-weight-bolder text-success mb-0">{{ $personalStats['approved'] }}</h5>
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
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Rejected</p>
                <h5 class="font-weight-bolder text-danger mb-0">{{ $personalStats['rejected'] }}</h5>
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

  <!-- KPI Section -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-chart-pie-35 text-primary me-2"></i>
        Key Performance Indicators
      </h5>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Approval Rate</p>
                <h5 class="font-weight-bolder text-primary mb-0">{{ $kpi['approval_rate'] }}%</h5>
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
      <div class="card staff-card">
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
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Dokumen Bulan Ini</p>
                <h5 class="font-weight-bolder text-success mb-0">{{ $kpi['documents_this_month'] }}</h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-success shadow-success text-center border-radius-md">
                <i class="ni ni-calendar-grid-58 text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-4">
      <div class="card staff-card">
        <div class="card-body p-3">
          <div class="row">
            <div class="col-8">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold text-secondary">Total Approval</p>
                <h5 class="font-weight-bolder text-warning mb-0">{{ $kpi['total_approval'] }}</h5>
              </div>
            </div>
            <div class="col-4 text-end">
              <div class="icon icon-shape bg-gradient-warning shadow-warning text-center border-radius-md">
                <i class="ni ni-check-bold text-white opacity-10"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="chart-container">
        <h6 class="font-weight-bold text-dark mb-3">
          <i class="ni ni-chart-bar-32 text-primary me-2"></i>
          Grafik Dokumen per Bulan ({{ date('Y') }})
        </h6>
        <div style="position: relative; height: 400px; width: 100%;">
          <canvas id="personalDocumentChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Documents -->
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-single-copy-04 text-primary me-2"></i>
        Dokumen Terbaru Anda
      </h5>
    </div>
    <div class="col-12">
      <div class="card staff-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-single-copy-04 text-info me-2"></i>
                Dokumen Terbaru
              </h6>
              <p class="text-sm text-secondary mb-0">Dokumen terbaru yang Anda buat</p>
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
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentDocuments as $document)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ Str::limit($document->title, 30) }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ $document->documentType->name }}</p>
                        </div>
                      </div>
                    </td>
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
                    <td class="align-middle text-center">
                      <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info btn-sm">
                        <i class="ni ni-world-2"></i> Lihat
                      </a>
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
              <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm mt-2">
                <i class="ni ni-fat-add"></i> Buat Dokumen Pertama
              </a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Approval History -->
  @if($approvalHistory->count() > 0)
  <div class="row mb-4">
    <div class="col-12">
      <h5 class="section-title">
        <i class="ni ni-time-alarm text-primary me-2"></i>
        Riwayat Approval
      </h5>
    </div>
    <div class="col-12">
      <div class="card staff-card">
        <div class="card-header pb-0">
          <div class="row align-items-center">
            <div class="col">
              <h6 class="font-weight-bold text-dark mb-0">
                <i class="ni ni-time-alarm text-info me-2"></i>
                Riwayat Approval
              </h6>
              <p class="text-sm text-secondary mb-0">Riwayat approval dokumen Anda</p>
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
</div>

@if($chartData->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Personal Document Chart
  var chartData = @json($chartData);
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var dataArr = new Array(12).fill(0);
  chartData.forEach(function(item) {
    dataArr[item.month-1] = item.count;
  });
  
  var ctx = document.getElementById('personalDocumentChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Jumlah Dokumen',
        data: dataArr,
        borderColor: '#5e72e4',
        backgroundColor: 'rgba(94, 114, 228, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4,
        pointBackgroundColor: '#5e72e4',
        pointBorderColor: '#ffffff',
        pointBorderWidth: 2,
        pointRadius: 6,
        pointHoverRadius: 8
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            font: {
              size: 14,
              weight: 'bold'
            },
            padding: 20
          }
        },
        tooltip: {
          backgroundColor: 'rgba(0, 0, 0, 0.8)',
          titleColor: '#ffffff',
          bodyColor: '#ffffff',
          borderColor: '#5e72e4',
          borderWidth: 1,
          cornerRadius: 8,
          displayColors: false
        }
      },
      scales: {
        x: {
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            font: {
              size: 12,
              weight: 'bold'
            },
            color: '#344767'
          }
        },
        y: {
          beginAtZero: true,
          grid: {
            display: true,
            color: 'rgba(0, 0, 0, 0.1)'
          },
          ticks: {
            font: {
              size: 12,
              weight: 'bold'
            },
            color: '#344767',
            callback: function(value) {
              return value + ' dokumen';
            }
          }
        }
      },
      interaction: {
        intersect: false,
        mode: 'index'
      },
      elements: {
        point: {
          hoverBackgroundColor: '#5e72e4'
        }
      }
    }
  });
});
</script>
@endif
@endsection 