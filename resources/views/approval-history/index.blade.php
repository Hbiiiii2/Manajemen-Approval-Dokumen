@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>History Approval Dokumen</h6>
          <p class="text-sm">
            <i class="ni ni-time-alarm text-info"></i>
            <span class="font-weight-bold">Riwayat dokumen yang pernah diapprove</span>
          </p>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          <!-- Statistik -->
          <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
              <div class="card">
                <div class="card-header p-3 pt-2">
                  <div class="icon icon-lg icon-shape bg-gradient-success shadow text-center border-radius-xl mt-n4 position-absolute">
                    <i class="ni ni-check-bold opacity-10"></i>
                  </div>
                  <div class="text-end pt-1">
                    <p class="text-sm mb-0 text-capitalize">Total Approved</p>
                    <h4 class="mb-0">{{ $stats['total_approved'] }}</h4>
                  </div>
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
                    <h4 class="mb-0">{{ $stats['total_rejected'] }}</h4>
                  </div>
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
                    <p class="text-sm mb-0 text-capitalize">Total Pending</p>
                    <h4 class="mb-0">{{ $stats['total_pending'] }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>

          @if($approvalHistory->count() > 0)
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Level</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
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
                      <span class="badge badge-sm bg-gradient-secondary">{{ $approval->level->name }}</span>
                    </td>
                    <td class="align-middle text-center">
                      @if($approval->status == 'approved')
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      @elseif($approval->status == 'rejected')
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $approval->approved_at ? $approval->approved_at->format('d/m/Y H:i') : '-' }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="{{ route('approval-history.show', $approval->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
              {{ $approvalHistory->links() }}
            </div>
            <div class="text-center text-muted small">
              Menampilkan {{ $approvalHistory->firstItem() }} sampai {{ $approvalHistory->lastItem() }} dari {{ $approvalHistory->total() }} data
            </div>
          @else
            <div class="text-center py-4">
              <i class="ni ni-check-bold text-secondary" style="font-size: 3rem;"></i>
              <h6 class="mt-3">Tidak ada history approval</h6>
              <p class="text-sm text-secondary">Belum ada dokumen yang pernah diapprove.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 