@extends('layouts.app')

@section('content')
<div class="card p-4 mb-4">
    <h4 class="mb-3">Daftar Dokumen</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="table-responsive">
        <table class="table align-items-center mb-0">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                    <tr>
                        <td>{{ $doc->title }}</td>
                        <td>{{ $doc->documentType->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-gradient-{{ $doc->status == 'approved' ? 'success' : ($doc->status == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($doc->status) }}</span>
                        </td>
                        <td>{{ $doc->user->name ?? '-' }}</td>
                        <td><a href="{{ route('documents.show', $doc->id) }}" class="btn btn-info btn-sm">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $documents->links() }}</div>
</div>
@endsection 