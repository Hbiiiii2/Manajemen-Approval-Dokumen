@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Approval Dokumen</h6>
          <p class="text-sm">
            <i class="fa fa-info text-info"></i>
            <span class="font-weight-bold">Role: {{ ucfirst($role) }}</span>
          </p>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          @if($documents->count() > 0)
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($documents as $document)
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $document->title }}</h6>
                          <p class="text-xs text-secondary mb-0">{{ Str::limit($document->description, 50) }}</p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">{{ $document->user->name }}</p>
                      <p class="text-xs text-secondary mb-0">{{ $document->user->email }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-secondary">{{ $document->documentType->name }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="badge badge-sm bg-gradient-warning">Pending</span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="btn-group" role="group">
                        <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info btn-sm" title="Detail Dokumen">
                          <i class="fas fa-eye"></i> Detail
                        </a>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $document->id }}">
                          <i class="fas fa-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $document->id }}">
                          <i class="fas fa-times"></i> Reject
                        </button>
                      </div>
                    </td>
                  </tr>

                  <!-- Approve Modal -->
                  <div class="modal fade" id="approveModal{{ $document->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $document->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="approveModalLabel{{ $document->id }}">Approve Dokumen</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('approvals.approve', $document->id) }}" method="POST">
                          @csrf
                          <div class="modal-body">
                            <p>Apakah Anda yakin ingin approve dokumen <strong>{{ $document->title }}</strong>?</p>
                            <div class="form-group">
                              <label for="comment{{ $document->id }}">Komentar (opsional):</label>
                              <textarea class="form-control" id="comment{{ $document->id }}" name="comment" rows="3" placeholder="Masukkan komentar..."></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Approve</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>

                  <!-- Reject Modal -->
                  <div class="modal fade" id="rejectModal{{ $document->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $document->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="rejectModalLabel{{ $document->id }}">Reject Dokumen</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('approvals.reject', $document->id) }}" method="POST">
                          @csrf
                          <div class="modal-body">
                            <p>Apakah Anda yakin ingin reject dokumen <strong>{{ $document->title }}</strong>?</p>
                            <div class="form-group">
                              <label for="rejectComment{{ $document->id }}">Alasan Reject:</label>
                              <textarea class="form-control" id="rejectComment{{ $document->id }}" name="comment" rows="3" placeholder="Masukkan alasan reject..." required></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4">
              <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
              <h6 class="mt-3">Tidak ada dokumen yang perlu diapprove</h6>
              <p class="text-sm text-secondary">Semua dokumen sudah diproses atau tidak ada dokumen pending.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 