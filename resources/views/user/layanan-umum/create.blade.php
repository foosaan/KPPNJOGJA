@extends('user.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h2 class="h4 mb-0">Ajukan Layanan Umum</h2>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('umum.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">No Berkas</label>
                                <input type="text" class="form-control" value="{{ 'UMUM-' . now()->format('Ymd') }}"
                                    readonly>
                            </div>

                            <div class="mb-3">
                                <label for="id_satker" class="form-label">ID Satker</label>
                                <input type="text" name="id_satker" id="id_satker"
                                    class="form-control @error('id_satker') is-invalid @enderror"
                                    value="{{ $userNip ?? Auth::user()->nip }}" readonly required>
                                @error('id_satker')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis_layanan" class="form-label">Jenis Layanan</label>
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
                                    required>{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="file_upload" class="form-label">Upload File</label>
                                <input type="file" name="file_upload" id="file_upload"
                                    class="form-control @error('file_upload') is-invalid @enderror"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.png,.zip,.rar" required>
                                @error('file_upload')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP, RAR
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger text-white">
                                    <i class="bi bi-send-fill me-2"></i>
                                    Kirim Pengajuan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection