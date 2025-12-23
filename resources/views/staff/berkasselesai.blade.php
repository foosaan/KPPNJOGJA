@extends('staff.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Berkas Selesai</h2>

    @php
        $divisi = strtoupper(Auth::user()->divisi);
    @endphp

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="layananTabs" role="tablist">
        @if($divisi === 'VERA')
            <li class="nav-item"><a class="nav-link active" id="vera-tab" data-toggle="tab" href="#vera" role="tab">Layanan Vera</a></li>
        @elseif($divisi === 'PD')
            <li class="nav-item"><a class="nav-link active" id="pd-tab" data-toggle="tab" href="#pd" role="tab">Layanan PD</a></li>
        @elseif($divisi === 'MSKI')
            <li class="nav-item"><a class="nav-link active" id="mski-tab" data-toggle="tab" href="#mski" role="tab">Layanan MSKI</a></li>
        @elseif($divisi === 'BANK')
            <li class="nav-item"><a class="nav-link active" id="bank-tab" data-toggle="tab" href="#bank" role="tab">Layanan Bank</a></li>
        @endif
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">

        @if($divisi === 'VERA')
        <div class="tab-pane fade show active" id="vera" role="tabpanel">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($veraRequests as $request)
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'vera']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="baru" {{ $request->status=='baru'?'selected':'' }}>Baru</option>
                                            <option value="diproses" {{ $request->status=='diproses'?'selected':'' }}>Diproses</option>
                                            <option value="selesai" {{ $request->status=='selesai'?'selected':'' }}>Selesai</option>
                                            <option value="ditolak" {{ $request->status=='ditolak'?'selected':'' }}>Ditolak</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Tidak ada data layanan Vera</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @elseif($divisi === 'PD')
        <div class="tab-pane fade show active" id="pd" role="tabpanel">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pdRequests as $request)
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'pd']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="baru" {{ $request->status=='baru'?'selected':'' }}>Baru</option>
                                            <option value="diproses" {{ $request->status=='diproses'?'selected':'' }}>Diproses</option>
                                            <option value="selesai" {{ $request->status=='selesai'?'selected':'' }}>Selesai</option>
                                            <option value="ditolak" {{ $request->status=='ditolak'?'selected':'' }}>Ditolak</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Tidak ada data layanan PD</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @elseif($divisi === 'MSKI')
        <div class="tab-pane fade show active" id="mski" role="tabpanel">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mskiRequests as $request)
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'mski']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="baru" {{ $request->status=='baru'?'selected':'' }}>Baru</option>
                                            <option value="diproses" {{ $request->status=='diproses'?'selected':'' }}>Diproses</option>
                                            <option value="selesai" {{ $request->status=='selesai'?'selected':'' }}>Selesai</option>
                                            <option value="ditolak" {{ $request->status=='ditolak'?'selected':'' }}>Ditolak</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Tidak ada data layanan MSKI</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @elseif($divisi === 'BANK')
        <div class="tab-pane fade show active" id="bank" role="tabpanel">
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
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bankRequests as $request)
                            <tr>
                                <td>{{ $request->no_berkas }}</td>
                                <td>{{ $request->user->name ?? '-' }}</td>
                                <td>{{ $request->jenis_layanan }}</td>
                                <td>{{ $request->keterangan ?? '-' }}</td>
                                <td>
                                    @if($request->file_path)
                                        <a href="{{ asset('storage/'.$request->file_path) }}" target="_blank">Lihat File</a>
                                    @else - @endif
                                </td>
                                <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ ucfirst($request->status ?? '-') }}</td>
                                <td>
                                    <form action="{{ route('staff.updateStatus', [$request->id, 'bank']) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="baru" {{ $request->status=='baru'?'selected':'' }}>Baru</option>
                                            <option value="diproses" {{ $request->status=='diproses'?'selected':'' }}>Diproses</option>
                                            <option value="selesai" {{ $request->status=='selesai'?'selected':'' }}>Selesai</option>
                                            <option value="ditolak" {{ $request->status=='ditolak'?'selected':'' }}>Ditolak</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center">Tidak ada data layanan Bank</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
