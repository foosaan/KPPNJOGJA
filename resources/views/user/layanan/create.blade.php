@extends('user.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-folder-open"></i> 
                            Ajukan Layanan {{ $divisi->nama }}
                        </h2>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        @if(count($jenis_layanan) == 0)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Belum ada layanan tersedia untuk divisi <strong>{{ $divisi->nama }}</strong>.
                                <br>Silakan hubungi admin untuk menambahkan layanan.
                            </div>
                        @else
                            <form action="{{ route('layanan.generik.store', $divisi->slug) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">No Berkas</label>
                                    <input type="text" class="form-control" value="{{ strtoupper($divisi->slug) . '-' . now()->format('Ymd') }}"
                                        readonly>
                                    <small class="form-text text-muted">Nomor berkas akan digenerate otomatis</small>
                                </div>

                                <div class="mb-3">
                                    <label for="id_satker" class="form-label">ID Satker</label>
                                    <input type="text" name="id_satker" id="id_satker"
                                        class="form-control"
                                        value="{{ $userNip }}" readonly required>
                                </div>

                                <div class="mb-3">
                                    <label for="jenis_layanan" class="form-label">Jenis Layanan <span class="text-danger">*</span></label>
                                    <select name="jenis_layanan" id="jenis_layanan"
                                        class="form-select @error('jenis_layanan') is-invalid @enderror" required>
                                        <option value="">-- Pilih Jenis Layanan --</option>
                                        @foreach($jenis_layanan as $layanan)
                                            <option value="{{ $layanan }}" {{ old('jenis_layanan') == $layanan ? 'selected' : '' }}>
                                                {{ $layanan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_layanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">
                                        Keterangan <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="keterangan" id="keterangan" rows="3"
                                        class="form-control @error('keterangan') is-invalid @enderror"
                                        placeholder="Tambahkan keterangan..."
                                        required>{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="file_upload" class="form-label">Upload File <span class="text-danger">*</span></label>
                                    <input type="file" name="file_upload" id="file_upload"
                                        class="form-control @error('file_upload') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
                                    @error('file_upload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, RAR
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                                    </button>
                                </div>

                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
