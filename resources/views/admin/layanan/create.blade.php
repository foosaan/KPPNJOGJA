@extends('admin.app')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-plus-circle"></i> Tambah Layanan Baru
    </h1>
    <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    <i class="fas fa-edit"></i> Form Tambah Layanan
                </h6>
            </div>

            <div class="card-body">

                {{-- Error Message --}}
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

                <form action="{{ route('admin.layanan.store') }}" method="POST">
                    @csrf

                    <!-- Tipe Layanan -->
<div class="form-group">
    <label for="layanan_type" class="font-weight-bold">
        Tipe Layanan <span class="text-danger">*</span>
    </label>
    <input type="text"
           name="layanan_type"
           id="layanan_type"
           value="{{ old('layanan_type') }}"
           class="form-control @error('layanan_type') is-invalid @enderror"
           placeholder="Contoh: Vera, PD, MSKI, Bank, Umum"
           required>
    @error('layanan_type')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="form-text text-muted">
        <i class="fas fa-info-circle"></i> Ketik nama divisi/tipe layanan (contoh: Vera, Umum, Kepegawaian)
    </small>
</div>

                    {{-- Jenis Layanan --}}
                    <div class="form-group">
                        <label for="jenis_layanan" class="font-weight-bold">
                            Jenis Layanan <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="jenis_layanan"
                               id="jenis_layanan"
                               value="{{ old('jenis_layanan') }}"
                               class="form-control @error('jenis_layanan') is-invalid @enderror"
                               placeholder="Contoh: KONFIRMASI PENERIMAAN"
                               required>
                        @error('jenis_layanan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi Singkat --}}
                    <div class="form-group">
                        <label for="deskripsi" class="font-weight-bold">
                            Deskripsi Singkat <small class="text-muted">(opsional, max 150 karakter)</small>
                        </label>
                        <input type="text"
                               name="deskripsi"
                               id="deskripsi"
                               maxlength="150"
                               value="{{ old('deskripsi') }}"
                               class="form-control @error('deskripsi') is-invalid @enderror"
                               placeholder="Contoh: Layanan untuk konfirmasi penerimaan">
                        @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status Aktif --}}
                    <div class="form-group mt-4">
                        <div class="custom-control custom-switch custom-switch-lg">
                            <input type="checkbox" 
                                   class="custom-control-input"
                                   id="is_active"
                                   name="is_active"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold" for="is_active">
                                Status Aktif
                            </label>
                        </div>
                    </div>

                    <hr>

                    {{-- Tombol --}}
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Simpan Layanan</span>
                        </button>

                        <a href="{{ route('admin.layanan.index') }}" class="btn btn-secondary btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-times"></i>
                            </span>
                            <span class="text">Batal</span>
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
