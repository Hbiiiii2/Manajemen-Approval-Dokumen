@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  @if(auth()->user()->role->name == 'admin')
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h6>Filter Dashboard</h6>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('dashboard') }}" class="row">
            <div class="col-md-4">
              <label for="division_filter" class="form-label">Filter Divisi</label>
              <select class="form-control" id="division_filter" name="division_filter">
                <option value="">Semua Divisi</option>
                @foreach(\App\Models\Division::where('status', 'active')->get() as $division)
                  <option value="{{ $division->id }}" {{ request('division_filter') == $division->id ? 'selected' : '' }}>
                    {{ $division->name }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button type="submit" class="btn btn-primary me-2">Filter</button>
              <a href="{{ route('dashboard') }}" class="btn btn-secondary">Reset</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-single-copy-04 opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Dokumen</p>
            <h4 class="mb-0">{{ $stats['total'] }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Role: {{ ucfirst($role) }}</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-warning shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-time-alarm opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Pending</p>
            <h4 class="mb-0">{{ $stats['pending'] }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-warning text-sm font-weight-bolder">Menunggu Approval</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-success shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-check-bold opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Approved</p>
            <h4 class="mb-0">{{ $stats['approved'] }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Dokumen Disetujui</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-danger shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-fat-remove opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Rejected</p>
            <h4 class="mb-0">{{ $stats['rejected'] }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Dokumen Ditolak</span></p>
        </div>
      </div>
    </div>
  </div>

  @if(in_array($role, ['manager', 'section_head']))
  <div class="row mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-info shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-check-bold opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">To Approve</p>
            <h4 class="mb-0">{{ $stats['to_approve'] }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-info text-sm font-weight-bolder">Perlu Approval</span></p>
        </div>
      </div>
    </div>
  </div>
  @endif

  @if(in_array($role, ['manager', 'depthead', 'section_head', 'admin']))
  <div class="row mt-4">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-success shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-check-bold opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Approved</p>
            <h4 class="mb-0">{{ \App\Models\Approval::where('user_id', auth()->id())->where('status', 'approved')->count() }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-success text-sm font-weight-bolder">Dokumen Disetujui</span></p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
      <div class="card">
        <div class="card-header p-3 pt-2">
          <div class="icon icon-lg icon-shape bg-gradient-danger shadow text-center border-radius-xl mt-n4 position-absolute">
            <i class="ni ni-fat-remove opacity-10"></i>
          </div>
          <div class="text-end pt-1">
            <p class="text-sm mb-0 text-capitalize">Total Rejected</p>
            <h4 class="mb-0">{{ \App\Models\Approval::where('user_id', auth()->id())->where('status', 'rejected')->count() }}</h4>
          </div>
        </div>
        <hr class="dark horizontal">
        <div class="card-footer p-3">
          <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">Dokumen Ditolak</span></p>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="row mt-4">
    <!-- Dokumen Terbaru -->
    <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
      <div class="card">
        <div class="card-header pb-0">
          <div class="row">
            <div class="col-lg-6 col-7 my-auto">
              <h6>Dokumen Terbaru</h6>
              <p class="text-sm mb-0">
                <i class="ni ni-single-copy-04 text-info"></i>
                <span class="font-weight-bold">Dokumen terbaru yang dibuat</span>
              </p>
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

    @if(in_array($role, ['manager', 'section_head']))
    <!-- Dokumen Pending Approval -->
    <div class="col-lg-6 col-md-6 mb-md-0 mb-4">
      <div class="card">
        <div class="card-header pb-0">
          <div class="row">
            <div class="col-lg-6 col-7 my-auto">
              <h6>Pending Approval</h6>
              <p class="text-sm mb-0">
                <i class="ni ni-time-alarm text-warning"></i>
                <span class="font-weight-bold">Dokumen yang perlu diapprove</span>
              </p>
            </div>
            <div class="col-lg-6 col-5 my-auto text-end">
              <a href="{{ route('approval-history.index') }}" class="btn btn-info btn-sm">
                <i class="ni ni-time-alarm"></i> History Approval
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

  @if(in_array($role, ['manager', 'section_head']) && $approvalHistory->count() > 0)
  <!-- Riwayat Approval -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Riwayat Approval</h6>
          <p class="text-sm mb-0">
            <i class="ni ni-time-alarm text-info"></i>
            <span class="font-weight-bold">Riwayat approval yang telah dilakukan</span>
          </p>
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

  @if($chartData->count() > 0)
  <!-- Chart Statistik -->
  <div class="row mt-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Statistik Dokumen ({{ date('Y') }})</h6>
          <p class="text-sm mb-0">
            <i class="ni ni-chart-bar-32 text-info"></i>
            <span class="font-weight-bold">Grafik dokumen per bulan</span>
          </p>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="documentChart" class="chart-canvas" height="300"></canvas>
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
  var ctx = document.getElementById('documentChart').getContext('2d');
  var chartData = @json($chartData);
  
  var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  var data = new Array(12).fill(0);
  
  chartData.forEach(function(item) {
    data[item.month - 1] = item.count;
  });
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Jumlah Dokumen',
        data: data,
        borderColor: '#5e72e4',
        backgroundColor: 'rgba(94, 114, 228, 0.1)',
        borderWidth: 2,
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        }
      }
    }
  });
});
</script>
@endif
@endsection

