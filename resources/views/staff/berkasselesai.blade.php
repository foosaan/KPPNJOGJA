@extends('staff.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Berkas Selesai</h2>

    @php
        $divisi = strtoupper(Auth::user()->divisi);
        $requestsVar = match($divisi) {
            'UMUM' => $umumRequests,
            'BANK' => $bankRequests,
            'VERA' => $veraRequests,
            'PD' => $pdRequests,
            'MSKI' => $mskiRequests,
            default => collect(),
        };
        $routeDivisi = strtolower($divisi);
    @endphp

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="layananTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="{{ $routeDivisi }}-tab" data-toggle="tab" href="#{{ $routeDivisi }}" role="tab">
                Layanan {{ ucfirst($routeDivisi) }}
            </a>
        </li>
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">
        <div class="tab-pane fade show active" id="{{ $routeDivisi }}" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Nama User</th>
                            <th>Jenis Layanan</th>
                            <th>Keterangan</th>
                            <th>File</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Feedback</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requestsVar as $request)
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
                            <td>{{ ucfirst($request->status ?? '-') }}</td>
                            <td>
                                @if($request->feedback)
                                    <div><strong>{{ $request->feedback }}</strong></div>
                                    @if($request->feedback_file)
                                        <a href="{{ asset('storage/' . $request->feedback_file) }}" target="_blank">
                                            Lihat File Feedback
                                        </a>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                {{-- Update status --}}
                                <form action="{{ route('staff.updateStatus', [$request->id, $routeDivisi]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </form>

                                {{-- Form feedback hanya jika selesai & belum ada feedback --}}
                                @if($request->status == 'selesai' && !$request->feedback)
                                    <form action="{{ route('staff.feedback.update', $request->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="feedback" class="form-control form-control-sm" placeholder="Isi feedback..." required></textarea>
                                        <input type="file" name="feedback_file" class="form-control form-control-sm mt-1">
                                        <button type="submit" class="btn btn-primary btn-sm mt-1">Simpan Feedback</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data layanan {{ ucfirst($routeDivisi) }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
