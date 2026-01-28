@extends('layouts.staff.app')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="mb-4">Berkas Ditolak</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" style="min-width: 1200px;">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 12%;">No Berkas</th>
                                <th style="width: 10%;">Nama User</th>
                                <th style="width: 18%;">Jenis Layanan</th>
                                <th style="width: 12%;">Keterangan</th>
                                <th style="width: 6%;">File</th>
                                <th style="width: 10%;">Tanggal</th>
                                <th style="width: 7%;">Status</th>
                                <th style="width: 12%;">Feedback Terkirim</th>
                                <th style="width: 13%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allRequests as $request)
                                <tr>
                                    <td>{{ $request->no_berkas }}</td>
                                    <td>{{ $request->user->name ?? '-' }}</td>
                                    <td>{{ $request->jenis_layanan }}</td>
                                    <td>{{ $request->keterangan ?? '-' }}</td>
                                    <td>
                                        @if($request->file_path)
                                            <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat File</a>
                                        @else - @endif
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td><span class="badge badge-danger">{{ ucfirst($request->status ?? '-') }}</span></td>
                                    <td>
                                        @if($request->feedback || $request->feedback_file)
                                            <span class="badge badge-success mb-1">
                                                <i class="fas fa-check-circle"></i> Sudah Dikirim
                                            </span>
                                            @if($request->feedback_file)
                                                <br>
                                                <a href="{{ asset('storage/' . $request->feedback_file) }}" 
                                                    target="_blank" class="btn btn-sm btn-outline-success mt-1">
                                                    <i class="fas fa-file-download"></i> Lihat File
                                                </a>
                                            @endif
                                            @if($request->feedback)
                                                <br>
                                                <small class="text-muted" title="{{ $request->feedback }}">
                                                    {{ Str::limit($request->feedback, 30) }}
                                                </small>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-clock"></i> Belum Ada
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Feedback Button -->
                                            @if($request->feedback || $request->feedback_file)
                                                <button type="button" class="btn btn-sm btn-success" data-toggle="modal" 
                                                    data-target="#feedbackModal{{ $request->id }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" 
                                                    data-target="#feedbackModal{{ $request->id }}">
                                                    <i class="fas fa-comment-alt"></i> Feedback
                                                </button>
                                            @endif
                                            
                                            <!-- Status Dropdown -->
                                            <form action="{{ route('staff.updateStatus', [$request->id, $request->layanan_type ?? 'generik']) }}" 
                                                method="POST" class="ml-1">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Feedback Modal -->
                                <div class="modal fade" id="feedbackModal{{ $request->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form action="{{ route('staff.feedback.update', $request->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header bg-warning">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-comment-alt"></i> Berikan Feedback / Alasan Penolakan
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <strong>No Berkas:</strong> {{ $request->no_berkas }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Layanan:</strong> {{ $request->jenis_layanan }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="feedback{{ $request->id }}"><strong>Feedback / Alasan Penolakan</strong></label>
                                                        <textarea name="feedback" id="feedback{{ $request->id }}" class="form-control" rows="4" 
                                                            placeholder="Jelaskan alasan penolakan atau feedback untuk user..." 
                                                            required>{{ $request->feedback ?? $request->alasan_penolakan ?? '' }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label><strong>File Feedback (opsional)</strong></label>
                                                        <input type="file" name="feedback_file" class="form-control-file" 
                                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                        <small class="text-muted">Format: PDF, JPG, PNG, DOC, DOCX (Max 2MB)</small>
                                                        @if($request->feedback_file)
                                                            <div class="mt-2">
                                                                <i class="fas fa-file"></i> File saat ini: 
                                                                <a href="{{ asset('storage/' . $request->feedback_file) }}" target="_blank" class="text-primary">
                                                                    Lihat File
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                        <i class="fas fa-times"></i> Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-paper-plane"></i> Kirim Feedback
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data berkas ditolak</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
