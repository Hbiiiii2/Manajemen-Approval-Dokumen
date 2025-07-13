@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Edit Dokumen</h6>
          <p class="text-sm">
            <i class="fa fa-info text-info"></i>
            <span class="font-weight-bold">Role: {{ ucfirst(auth()->user()->role->name) }}</span>
          </p>
        </div>
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('documents.update', $document->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title" class="form-control-label">Judul Dokumen</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $document->title) }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="document_type_id" class="form-control-label">Tipe Dokumen</label>
                  <select class="form-control" id="document_type_id" name="document_type_id" required>
                    <option value="">Pilih Tipe Dokumen</option>
                    @foreach($documentTypes as $documentType)
                      <option value="{{ $documentType->id }}" {{ old('document_type_id', $document->document_type_id) == $documentType->id ? 'selected' : '' }}>
                        {{ $documentType->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="division_id" class="form-control-label">Divisi</label>
                  <select class="form-control" id="division_id" name="division_id" required>
                    <option value="">Pilih Divisi</option>
                    @foreach($userDivisions as $division)
                      <option value="{{ $division->id }}" {{ old('division_id', $document->division_id) == $division->id ? 'selected' : '' }}>
                        {{ $division->name }} ({{ $division->code }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="description" class="form-control-label">Deskripsi</label>
                  <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $document->description) }}</textarea>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">Batal</a>
              <button type="submit" class="btn btn-cyan">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.btn-cyan {
  background: #16c7f9 !important;
  color: #fff !important;
  font-weight: bold;
  border: none;
  border-radius: 16px;
  padding: 8px 24px;
  box-shadow: 0 2px 8px rgba(22,199,249,0.08);
  transition: 0.2s;
}
.btn-cyan:hover, .btn-cyan:focus {
  background: #0bb3e3 !important;
  color: #fff !important;
  opacity: 0.95;
}
</style>
@endsection 