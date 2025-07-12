@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <div>
            <h6>Daftar Dokumen</h6>
            <p class="text-sm">
              <i class="fa fa-info text-info"></i>
              <span class="font-weight-bold">Role: {{ ucfirst(auth()->user()->role->name) }}</span>
            </p>
          </div>
          @if(in_array(auth()->user()->role->name, ['staff', 'manager']))
          <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Dokumen
          </a>
          @endif
        </div>
        <div class="card-body px-0 pt-0 pb-2">
          @if(auth()->user()->role->name == 'admin')
          <div class="row mb-3">
            <div class="col-md-4">
              <form method="GET" action="{{ route('documents.index') }}" class="d-flex">
                <select class="form-control me-2" name="division_filter">
                  <option value="">Semua Divisi</option>
                  @foreach(\App\Models\Division::where('status', 'active')->get() as $division)
                    <option value="{{ $division->id }}" {{ request('division_filter') == $division->id ? 'selected' : '' }}>
                      {{ $division->name }}
                    </option>
                  @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
              </form>
            </div>
          </div>
          @endif
          @if($documents->count() > 0)
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembuat</th>
                    @if(auth()->user()->role->name == 'admin')
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Divisi</th>
                    @endif
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
                    @if(auth()->user()->role->name == 'admin')
                    <td class="align-middle text-center text-sm">
                      @if($document->division)
                        <span class="badge badge-sm bg-gradient-secondary">{{ $document->division->name }}</span>
                      @else
                        <span class="badge badge-sm bg-gradient-warning">-</span>
                      @endif
                    </td>
                    @endif
                    <td class="align-middle text-center">
                      <span class="badge badge-sm bg-gradient-secondary">{{ $document->documentType->name }}</span>
                    </td>
                    <td class="align-middle text-center">
                      @if($document->status == 'pending')
                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                      @elseif($document->status == 'approved')
                        <span class="badge badge-sm bg-gradient-success">Approved</span>
                      @elseif($document->status == 'rejected')
                        <span class="badge badge-sm bg-gradient-danger">Rejected</span>
                      @endif
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                    </td>
                    <td class="align-middle text-center">
                      <a href="{{ route('documents.show', $document->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Detail
                      </a>
                      @if(auth()->user()->id == $document->user_id && $document->status == 'pending')
                      <a href="{{ route('documents.edit', $document->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
              {{ $documents->links() }}
            </div>
          @else
            <div class="text-center py-4">
              <i class="fas fa-file-alt text-secondary" style="font-size: 3rem;"></i>
              <h6 class="mt-3">Tidak ada dokumen</h6>
              <p class="text-sm text-secondary">Belum ada dokumen yang dibuat.</p>
              @if(in_array(auth()->user()->role->name, ['staff', 'manager']))
              <a href="{{ route('documents.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus"></i> Buat Dokumen Pertama
              </a>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection 