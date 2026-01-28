@extends('layouts.admin.app')

@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-edit"></i> Edit Layanan
    </h1>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-warning">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Edit Layanan
                </h6>
            </div>
            <div class="card-body">
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
                @endif

                <form action="{{ route('admin.layanan.update', $layanan) }}" method="POST" id="formEditLayanan">
                    @csrf
                    @method('PUT')

                    <!-- Pilih Divisi -->
                    <div class="form-group">
                        <label for="divisi_id" class="font-weight-bold">
                            Divisi <span class="text-danger">*</span>
                        </label>
                        <select name="divisi_id" id="divisi_id" class="form-control @error('divisi_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisis as $divisi)
                                <option value="{{ $divisi->id }}" {{ old('divisi_id', $layanan->divisi_id) == $divisi->id ? 'selected' : '' }}>
                                    {{ $divisi->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('divisi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jenis Layanan -->
                    <div class="form-group">
                        <label for="jenis_layanan" class="font-weight-bold">
                            Jenis Layanan <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="jenis_layanan" 
                               id="jenis_layanan" 
                               class="form-control @error('jenis_layanan') is-invalid @enderror"
                               value="{{ old('jenis_layanan', $layanan->jenis_layanan) }}"
                               placeholder="Contoh: LAYANAN KONFIRMASI PENERIMAAN"
                               required>
                        @error('jenis_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="form-group">
                        <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                        <textarea name="deskripsi" 
                                  id="deskripsi" 
                                  rows="3"
                                  maxlength="150"
                                  class="form-control @error('deskripsi') is-invalid @enderror"
                                  placeholder="Deskripsi layanan (opsional, max 150 karakter)">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status Aktif -->
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" 
                                   class="custom-control-input" 
                                   id="is_active" 
                                   name="is_active"
                                   {{ old('is_active', $layanan->is_active) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Buttons -->
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Layanan
                        </button>
                        <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                        <button type="button" class="btn btn-danger float-right" 
                                data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card shadow mb-4 border-left-primary">
            <div class="card-body">
                <h6 class="font-weight-bold text-primary mb-3">
                    <i class="fas fa-info-circle"></i> Informasi Layanan:
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2"><strong>ID:</strong> #{{ $layanan->id }}</li>
                            <li class="mb-2"><strong>Dibuat:</strong> {{ $layanan->created_at->format('d M Y H:i') }}</li>
                            <li><strong>Update:</strong> {{ $layanan->updated_at->format('d M Y H:i') }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <strong>Divisi:</strong> 
                                <span class="badge badge-{{ $layanan->badge_color }}">
                                    {{ $layanan->divisi->nama ?? '-' }}
                                </span>
                            </li>
                            <li>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $layanan->is_active ? 'success' : 'secondary' }}">
                                    {{ $layanan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash"></i> Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus layanan <strong>{{ $layanan->jenis_layanan }}</strong>?</p>
                <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle"></i> Data yang dihapus tidak dapat dikembalikan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.layanan.destroy', $layanan) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection