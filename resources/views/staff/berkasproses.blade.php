@extends('staff.app')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="mb-4">Berkas Sedang Diproses</h2>

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
                    <table class="table table-striped table-hover" style="min-width: 1000px;">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 14%;">No Berkas</th>
                                <th style="width: 12%;">Nama User</th>
                                <th style="width: 20%;">Jenis Layanan</th>
                                <th style="width: 15%;">Keterangan</th>
                                <th style="width: 8%;">File</th>
                                <th style="width: 12%;">Tanggal</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 11%;">Aksi</th>
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
                                    <td><span class="badge badge-warning">{{ ucfirst($request->status ?? '-') }}</span></td>
                                    <td>
                                        <form action="{{ route('staff.updateStatus', [$request->id, $request->layanan_type ?? 'generik']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control form-control-sm"
                                                onchange="this.form.submit()" style="width: auto;">
                                                <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                                <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data berkas diproses</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection