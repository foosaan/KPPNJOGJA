@extends('layouts.staff.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Staff</h2>

    <!-- Staff Profile Section -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Profil Staff</h5>
        </div>
        <div class="card-body p-3">
    <div class="user-profile-compact">
        <div class="profile-item">
            <span class="profile-label">ID Staff:</span>
            <span class="profile-value">{{ Auth::user()->nip }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Nama:</span>
            <span class="profile-value">{{ Auth::user()->name }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Email:</span>
            <span class="profile-value">{{ Auth::user()->email }}</span>
        </div>
        <div class="profile-item">
            <span class="profile-label">Divisi:</span>
            <span class="profile-value">{{ Auth::user()->divisi }}</span>
        </div>
    </div>
</div>

    </div>

    <!-- Cards Total Layanan per Divisi -->
    <div class="row">
        @php
            $colors = ['primary', 'success', 'warning', 'danger', 'info', 'secondary', 'dark'];
            $colorIndex = 0;
        @endphp
        @foreach($divisiStats as $slug => $stat)
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-{{ $colors[$colorIndex % count($colors)] }}">
                <div class="card-body">
                    <h5 class="card-title">Layanan {{ $stat['nama'] }}</h5>
                    <p class="card-text display-4">{{ $stat['count'] }}</p>
                </div>
            </div>
        </div>
        @php $colorIndex++; @endphp
        @endforeach
    </div>

    <!-- Filter -->
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-control" id="filterStatus">
                <option value="">Semua Status</option>
                <option value="baru">Baru</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterBulan">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" id="filterTahun">
                <option value="">Semua Tahun</option>
                @php
                    $tahunList = $paginatedRequests->pluck('created_at')->map(fn($d) => $d->format('Y'))->unique()->sortDesc();
                @endphp
                @foreach ($tahunList as $tahun)
                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari No Berkas / Layanan / Keterangan">
        </div>
    </div>

    <!-- Semua Data Layanan Tabel -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Semua Data Layanan</h5>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped" id="layananTable">
                    <thead>
                        <tr>
                            <th>No Berkas</th>
                            <th>Layanan</th>
                            <th>Keterangan</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paginatedRequests as $request)
                        <tr>
                            <td>{{ $request->no_berkas }}</td>
                            <td>{{ $request->layanan_type }} - {{ $request->jenis_layanan ?? '-' }}</td>
                            <td>{{ $request->keterangan ?? '-' }}</td>
                            <td>{{ ucfirst($request->status ?? '-') }}</td>
                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data layanan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination Links Bootstrap 5 -->
                <div class="mt-3">
                    {{ $paginatedRequests->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Filter --}}
<script>
    function applyFilters() {
        const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        const bulanFilter = document.getElementById('filterBulan').value;
        const tahunFilter = document.getElementById('filterTahun').value;
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();

        document.querySelectorAll('#layananTable tbody tr').forEach(row => {
            const noBerkas = row.cells[0]?.textContent.trim().toLowerCase();
            const layanan = row.cells[1]?.textContent.trim().toLowerCase();
            const ket = row.cells[2]?.textContent.trim().toLowerCase();
            const status = row.cells[3]?.textContent.trim().toLowerCase();
            const tanggal = row.cells[4]?.textContent.trim();

            let show = true;

            if (statusFilter && status !== statusFilter) show = false;

            if (tanggal) {
                const parts = tanggal.split(' ')[0].split('/'); // dd/mm/yyyy
                const bulan = parts[1];
                const tahun = parts[2];
                if (bulanFilter && bulan !== bulanFilter) show = false;
                if (tahunFilter && tahun !== tahunFilter) show = false;
            }

            if (searchQuery) {
                const text = (noBerkas + ' ' + layanan + ' ' + ket);
                if (!text.includes(searchQuery)) show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    document.querySelectorAll('#filterStatus, #filterBulan, #filterTahun').forEach(el => {
        el.addEventListener('change', applyFilters);
    });

    document.getElementById('searchInput').addEventListener('keyup', applyFilters);
</script>
@endsection
