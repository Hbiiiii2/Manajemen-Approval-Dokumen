@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header pb-0">
          <h6>Tambah Dokumen Baru</h6>
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

          <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="title" class="form-control-label">Judul Dokumen</label>
                  <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="document_type_id" class="form-control-label">Tipe Dokumen</label>
                  <select class="form-control" id="document_type_id" name="document_type_id" required>
                    <option value="">Pilih Tipe Dokumen</option>
                    @foreach($documentTypes as $documentType)
                      <option value="{{ $documentType->id }}" {{ old('document_type_id') == $documentType->id ? 'selected' : '' }}>
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
                      <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }} ({{ $division->code }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="description" class="form-control-label">Deskripsi</label>
                  <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                </div>
              </div>
            </div>

            <!-- Multi-file upload section -->
            <div class="form-group">
              <label class="form-control-label">File Dokumen</label>
              <div id="file-upload-container">
                <div class="file-input-group mb-3">
                  <div class="row">
                    <div class="col-md-8">
                      <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="file_descriptions[]" placeholder="Deskripsi file (opsional)">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="mt-2">
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-file-btn">
                  <i class="fas fa-plus"></i> Tambah File
                </button>
                <small class="form-text text-muted">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG, JPEG (Max: 20MB per file)</small>
              </div>
            </div>

            <div class="d-flex justify-content-end">
              <a href="{{ route('documents.index') }}" class="btn btn-secondary me-2">Batal</a>
              <button type="submit" class="btn btn-primary">Simpan Dokumen</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('file-upload-container');
    const addBtn = document.getElementById('add-file-btn');
    let fileCount = 1;

    addBtn.addEventListener('click', function() {
        fileCount++;
        const newGroup = document.createElement('div');
        newGroup.className = 'file-input-group mb-3';
        newGroup.innerHTML = `
            <div class="row">
                <div class="col-md-8">
                    <input type="file" class="form-control" name="files[]" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="file_descriptions[]" placeholder="Deskripsi file (opsional)">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-file-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(newGroup);
        
        // Add remove functionality
        newGroup.querySelector('.remove-file-btn').addEventListener('click', function() {
            container.removeChild(newGroup);
        });
    });

    // Add remove functionality to first file input
    document.querySelector('.remove-file-btn')?.addEventListener('click', function() {
        if (fileCount > 1) {
            container.removeChild(this.closest('.file-input-group'));
            fileCount--;
        }
    });
});
</script>
@endsection 