@extends('admin.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    {{-- Ringkasan --}}
    <div class="row">
        <!-- Total Admin -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $admins->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Staff -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Staff</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $staffs->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Daftar Berkas Masuk --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Berkas Masuk</h6>
            <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari no berkas atau layanan...">
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="berkasTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab">Semua</a>
                </li>
                @foreach($divisis as $divisi)
                <li class="nav-item">
                    <a class="nav-link" id="{{ $divisi->slug }}-tab" data-toggle="tab" href="#{{ $divisi->slug }}" role="tab">{{ $divisi->nama }}</a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content mt-3" id="berkasTabContent">
                {{-- Semua Berkas --}}
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    <table class="table table-bordered berkas-table">
                        <thead>
                            <tr>
                                <th>No Berkas</th>
                                <th>Jenis Layanan</th>
                                <th>Divisi</th>
                                <th>Staff Pemroses</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allBerkas as $berkas)
                                <tr>
                                    <td>{{ $berkas->no_berkas }}</td>
                                    <td>{{ $berkas->jenis_layanan }}</td>
                                    <td><span class="badge badge-info">{{ $berkas->divisi_nama }}</span></td>
                                    <td>{{ $berkas->staff->name ?? '-' }}</td>
                                    <td>
                                        @if($berkas->status == 'baru')
                                            <span class="badge badge-warning">Baru</span>
                                        @elseif($berkas->status == 'diproses')
                                            <span class="badge badge-primary">Diproses</span>
                                        @elseif($berkas->status == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($berkas->status == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $berkas->status ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $berkas->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada berkas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Dynamic Divisi Tabs --}}
                @foreach($divisis as $divisi)
                <div class="tab-pane fade" id="{{ $divisi->slug }}" role="tabpanel">
                    <table class="table table-bordered berkas-table">
                        <thead>
                            <tr>
                                <th>No Berkas</th>
                                <th>Jenis Layanan</th>
                                <th>Staff Pemroses</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($berkasByDivisi[$divisi->slug] ?? [] as $berkas)
                                <tr>
                                    <td>{{ $berkas->no_berkas }}</td>
                                    <td>{{ $berkas->jenis_layanan }}</td>
                                    <td>{{ $berkas->staff->name ?? '-' }}</td>
                                    <td>
                                        @if($berkas->status == 'baru')
                                            <span class="badge badge-warning">Baru</span>
                                        @elseif($berkas->status == 'diproses')
                                            <span class="badge badge-primary">Diproses</span>
                                        @elseif($berkas->status == 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @elseif($berkas->status == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $berkas->status ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $berkas->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada berkas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Script Realtime Search --}}
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                let value = this.value.toLowerCase();
                document.querySelectorAll('.berkas-table tbody tr').forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(value) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush
