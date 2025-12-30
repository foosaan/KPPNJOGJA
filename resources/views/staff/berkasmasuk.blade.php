@extends('staff.app')

@section('content')
    <div class="container">

        <h2 class="mb-4">Berkas Masuk - Semua Layanan</h2>

        @php
            $divisi = strtoupper(Auth::user()->divisi);
        @endphp

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="layananTabs" role="tablist">
            @if($divisi === 'VERA')
                <li class="nav-item">
                    <a class="nav-link" id="vera-tab" data-toggle="tab" href="#vera" role="tab">Layanan Vera</a>
                </li>
            @endif
            @if($divisi === 'PD')
                <li class="nav-item">
                    <a class="nav-link" id="pd-tab" data-toggle="tab" href="#pd" role="tab">Layanan PD</a>
                </li>
            @endif
            @if($divisi === 'MSKI')
                <li class="nav-item">
                    <a class="nav-link" id="mski-tab" data-toggle="tab" href="#mski" role="tab">Layanan MSKI</a>
                </li>
            @endif
            @if($divisi === 'BANK')
                <li class="nav-item">
                    <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">Layanan Bank</a>
                </li>
            @endif

            @if($divisi === 'UMUM')
                <li class="nav-item">
                    <a class="nav-link" id="umum-tab" data-toggle="tab" href="#umum" role="tab">Layanan Umum</a>
                </li>
            @endif


        </ul>

        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="layananTabsContent">

            <!-- Tab Semua -->
            <div class="tab-pane fade show active" id="semua" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableSemua">
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
                            @php
                                $veraRequests->each(fn($r) => $r->layanan_type = 'vera');
                                $pdRequests->each(fn($r) => $r->layanan_type = 'pd');
                                $mskiRequests->each(fn($r) => $r->layanan_type = 'mski');
                                $bankRequests->each(fn($r) => $r->layanan_type = 'bank');
                                $umumRequests->each(fn($r) => $r->layanan_type = 'umum');

                                $allRequests = collect();
                                if ($divisi === 'VERA')
                                    $allRequests = $veraRequests;
                                if ($divisi === 'PD')
                                    $allRequests = $pdRequests;
                                if ($divisi === 'MSKI')
                                    $allRequests = $mskiRequests;
                                if ($divisi === 'BANK')
                                    $allRequests = $bankRequests;
                                if ($divisi === 'UMUM')
                                    $allRequests = $umumRequests;
                            @endphp

                            @forelse($allRequests as $request)
                                <tr>
                                    <td>{{ $request->no_berkas }}</td>
                                    <td>{{ $request->user->name ?? '-' }}</td>
                                    <td>{{ ucfirst($request->layanan_type) }} - {{ $request->jenis_layanan }}</td>
                                    <td>{{ $request->keterangan ?? '-' }}</td>
                                    <td>
                                        @if($request->file_path)
                                            @php
                                                // Gunakan original_filename dari database
                                                    $displayName = basename($request->file_path);
                                            @endphp
                                            <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                download="{{ $displayName }}">
                                                {{ $displayName }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ ucfirst($request->status ?? '-') }}</td>
                                    <td>
                                        <form action="{{ route('staff.updateStatus', [$request->id, $request->layanan_type]) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control form-control-sm"
                                                onchange="this.form.submit()">
                                                <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                </option>
                                                <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                    Diproses</option>
                                                <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                    Selesai
                                                </option>
                                                <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                    Ditolak
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data layanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab Vera -->
            @if($divisi === 'VERA')
                <div class="tab-pane fade" id="vera" role="tabpanel">
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
                                                @php
                                                    $extension = pathinfo($request->file_path, PATHINFO_EXTENSION);
                                                    $fileName = $request->no_berkas . '.' . $extension;
                                                @endphp
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                    download="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ucfirst($request->status ?? '-') }}</td>
                                        <td>
                                            <form action="{{ route('staff.updateStatus', [$request->id, 'vera']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                    </option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data layanan Vera</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tab PD -->
            @if($divisi === 'PD')
                <div class="tab-pane fade" id="pd" role="tabpanel">
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
                                                @php
                                                    $extension = pathinfo($request->file_path, PATHINFO_EXTENSION);
                                                    $fileName = $request->no_berkas . '.' . $extension;
                                                @endphp
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                    download="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ucfirst($request->status ?? '-') }}</td>
                                        <td>
                                            <form action="{{ route('staff.updateStatus', [$request->id, 'pd']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                    </option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data layanan PD</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tab MSKI -->
            @if($divisi === 'MSKI')
                <div class="tab-pane fade" id="mski" role="tabpanel">
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
                                                @php
                                                    $extension = pathinfo($request->file_path, PATHINFO_EXTENSION);
                                                    $fileName = $request->no_berkas . '.' . $extension;
                                                @endphp
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                    download="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ucfirst($request->status ?? '-') }}</td>
                                        <td>
                                            <form action="{{ route('staff.updateStatus', [$request->id, 'mski']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                    </option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data layanan MSKI</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tab Bank -->
            @if($divisi === 'BANK')
                <div class="tab-pane fade" id="bank" role="tabpanel">
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
                                                @php
                                                    $extension = pathinfo($request->file_path, PATHINFO_EXTENSION);
                                                    $fileName = $request->no_berkas . '.' . $extension;
                                                @endphp
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                    download="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ucfirst($request->status ?? '-') }}</td>
                                        <td>
                                            <form action="{{ route('staff.updateStatus', [$request->id, 'bank']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                    </option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data layanan Bank</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Tab Umum -->
            @if($divisi === 'UMUM')
                <div class="tab-pane fade" id="umum" role="tabpanel">
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
                                @forelse($umumRequests as $request)
                                    <tr>
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ $request->user->name ?? '-' }}</td>
                                        <td>{{ $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                @php
                                                    $extension = pathinfo($request->file_path, PATHINFO_EXTENSION);
                                                    $fileName = $request->no_berkas . '.' . $extension;
                                                @endphp
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank"
                                                    download="{{ $fileName }}">
                                                    {{ $fileName }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ ucfirst($request->status ?? '-') }}</td>
                                        <td>
                                            <form action="{{ route('staff.updateStatus', [$request->id, 'umum']) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control form-control-sm"
                                                    onchange="this.form.submit()">
                                                    <option value="baru" {{ $request->status == 'baru' ? 'selected' : '' }}>Baru
                                                    </option>
                                                    <option value="diproses" {{ $request->status == 'diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="selesai" {{ $request->status == 'selesai' ? 'selected' : '' }}>
                                                        Selesai
                                                    </option>
                                                    <option value="ditolak" {{ $request->status == 'ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data layanan Bank</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif



        </div>
    </div>
@endsection

@section('styles')
    <style>
        .user-profile-compact {
            line-height: 1.6;
        }

        .profile-item {
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
        }

        .profile-label {
            font-weight: bold;
            min-width: 100px;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .profile-value {
            flex: 1;
        }

        .card-body.p-3 {
            padding: 1rem !important;
        }

        .card-header {
            font-weight: bold;
            padding: 0.75rem 1rem;
        }

        .table th {
            white-space: nowrap;
        }
    </style>
@endsection