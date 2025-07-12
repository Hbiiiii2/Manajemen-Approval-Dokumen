@extends('layouts.app')

@section('content')
<div class="card p-4 mb-4">
    <h4 class="mb-3">Detail Dokumen</h4>
    <ul class="list-group mb-3">
        <li class="list-group-item"><b>Judul:</b> {{ $document->title }}</li>
        <li class="list-group-item"><b>Tipe:</b> {{ $document->documentType->name ?? '-' }}</li>
        <li class="list-group-item"><b>Status:</b> <span class="badge bg-gradient-{{ $document->status == 'approved' ? 'success' : ($document->status == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($document->status) }}</span></li>
        <li class="list-group-item"><b>User:</b> {{ $document->user->name ?? '-' }}</li>
        <li class="list-group-item"><b>Deskripsi:</b> {{ $document->description }}</li>
        <li class="list-group-item"><b>File:</b> <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">Download</a></li>
    </ul>
    <h5 class="mb-2">Riwayat Approval</h5>
    <div class="table-responsive mb-3">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->approvals as $approval)
                    <tr>
                        <td>{{ $approval->approvalLevel->name ?? '-' }}</td>
                        <td>{{ $approval->user->name ?? '-' }}</td>
                        <td><span class="badge bg-gradient-{{ $approval->status == 'approved' ? 'success' : ($approval->status == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($approval->status) }}</span></td>
                        <td>{{ $approval->notes }}</td>
                        <td>{{ $approval->approved_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($canApprove && $approvalLevel)
        <h5>Approval</h5>
        <form action="{{ route('documents.approve', $document->id) }}" method="POST" class="mb-3">
            @csrf
            <input type="hidden" name="level_id" value="{{ $approvalLevel->id }}">
            <div class="mb-2">
                <textarea name="notes" class="form-control" placeholder="Catatan (opsional)"></textarea>
            </div>
            <button type="submit" name="action" value="approved" class="btn btn-success">Approve</button>
            <button type="submit" name="action" value="rejected" class="btn btn-danger">Reject</button>
        </form>
    @endif
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
</div>
@endsection 