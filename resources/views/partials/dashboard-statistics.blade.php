<div class="row mb-4">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="text-xs text-secondary mb-1">Approval Rate</div>
        <h3 class="mb-0">{{ $kpi['approval_rate'] }}%</h3>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="text-xs text-secondary mb-1">Rata-rata Waktu Approval</div>
        <h3 class="mb-0">{{ $kpi['avg_approval_time'] < 1 ? round($kpi['avg_approval_time']*60) . ' menit' : number_format($kpi['avg_approval_time'],2) . ' jam' }}</h3>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="text-xs text-secondary mb-1">Total Approval</div>
        <h3 class="mb-0">{{ $kpi['total_approval'] }}</h3>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="text-xs text-secondary mb-1">Total Divisi</div>
        <h3 class="mb-0">{{ $kpi['total_division'] }}</h3>
      </div>
    </div>
  </div>
</div> 