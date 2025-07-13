@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-10">
      <div class="card shadow-sm">
        <div class="card-header bg-white pb-0">
          <h6>Edit File Dokumen</h6>
          <p class="text-sm mb-0">Dokumen: <b>{{ $document->title }}</b></p>
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
          <form action="{{ route('documents.files.update', $file->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label class="form-label">Nama File</label>
              <input type="text" class="form-control" name="original_name" value="{{ old('original_name', $file->original_name) }}" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea class="form-control" name="description" rows="3">{{ old('description', $file->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Ganti File (opsional)</label>
              <input type="file" class="form-control" name="replace_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
              <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
            </div>
            <div class="d-flex justify-content-between">
              <a href="{{ route('documents.show', $document->id) }}" class="btn btn-secondary">Batal</a>
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