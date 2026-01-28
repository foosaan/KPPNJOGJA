@extends('layouts.user.app')

@section('content')
    <div class="container">
        <!-- User Profile Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Profil Pengguna</h5>
            </div>
            <div class="card-body p-3">
                <div class="user-profile-compact">
                    <div class="profile-item">
                        <span class="profile-label">ID Satker:</span>
                        <span class="profile-value">{{ Auth::user()->nip }}</span>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Nama Petugas:</span>
                        <span class="profile-value">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Satuan Kerja:</span>
                        <span class="profile-value">{{ Auth::user()->nama_satker ?? '-' }}</span>
                    </div>
                    <div class="profile-item">
                        <span class="profile-label">Email:</span>
                        <span class="profile-value">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mb-4">Dashboard Layanan</h2>

        <!-- Filter -->
        {{-- 
            SECTION FILTER LENGKAP:
            User dapat memfilter data berdasarkan:
            1. Status (Baru/Proses/Ditolak)
            2. Bulan & Tahun pembuatan berkas
            3. Pencarian Teks (No Berkas)
            Logic ini dijalankan oleh Script JS di bawah (Client-Side).
        --}}
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
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-control" id="filterTahun">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari No Berkas / Layanan">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!-- Tab Navigation - Show all active divisi -->
                <ul class="nav nav-tabs" id="layananTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-filter="" role="tab">Semua</a>
                    </li>
                    @foreach($divisis as $divisi)
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-filter="{{ strtoupper($divisi->nama) }}" role="tab">
                                Layanan {{ $divisi->nama }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content p-3 border border-top-0 rounded-bottom">
                    <div class="table-responsive">
                        <table class="table table-striped" id="layananTable">
                            <thead>
                                <tr>
                                    <th>No Berkas</th>
                                    <th>Jenis Layanan</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Alasan Penolakan</th>
                                    <th>Feedback</th>
                                    <th>File Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allRequests as $request)
                                    <tr data-layanan-type="{{ strtoupper($request->layanan_type ?? '') }}"
                                        data-status="{{ strtolower($request->status ?? '') }}">
                                        <td>{{ $request->no_berkas }}</td>
                                        <td>{{ ($request->layanan_type ?? '') . ' - ' . $request->jenis_layanan }}</td>
                                        <td>{{ $request->keterangan ?? '-' }}</td>
                                        <td>
                                            @if($request->file_path)
                                                <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat File</a>
                                            @else - @endif
                                        </td>
                                        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($request->status) {
                                                    'baru' => 'badge-primary',
                                                    'diproses' => 'badge-warning',
                                                    'selesai' => 'badge-success',
                                                    'ditolak' => 'badge-danger',
                                                    default => 'badge-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst($request->status ?? '-') }}</span>
                                        </td>
                                        <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                        <td>{{ $request->feedback ?? '-' }}</td>
                                        <td>
                                            @if($request->feedback_file)
                                                <a href="{{ asset('storage/' . $request->feedback_file) }}" target="_blank">Download</a>
                                            @else - @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="empty-row">
                                        <td colspan="9" class="text-center">Tidak ada data layanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentLayananFilter = '';

    function applyFilters() {
        const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        const bulanFilter = document.getElementById('filterBulan').value;
        const tahunFilter = document.getElementById('filterTahun').value;
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();

        const rows = document.querySelectorAll('#layananTable tbody tr:not(.empty-row)');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowLayanan = row.dataset.layananType || '';
            const rowStatus = row.dataset.status || '';
            const noBerkas = row.cells[0]?.textContent.trim().toLowerCase() || '';
            const jenis = row.cells[1]?.textContent.trim().toLowerCase() || '';
            const ket = row.cells[2]?.textContent.trim().toLowerCase() || '';
            const tanggal = row.cells[4]?.textContent.trim() || '';

            let show = true;

            // Filter by layanan type (from tabs)
            if (currentLayananFilter && rowLayanan !== currentLayananFilter) {
                show = false;
            }

            // Filter status
            if (show && statusFilter && rowStatus !== statusFilter) {
                show = false;
            }

            // Filter bulan & tahun
            if (show && tanggal && (bulanFilter || tahunFilter)) {
                const parts = tanggal.split(' ')[0].split('/');
                if (parts.length >= 3) {
                    const bulan = parts[1];
                    const tahun = parts[2];

                    if (bulanFilter && bulan !== bulanFilter) {
                        show = false;
                    }
                    if (tahunFilter && tahun !== tahunFilter) {
                        show = false;
                    }
                }
            }

            // Filter search
            if (show && searchQuery) {
                const text = noBerkas + ' ' + jenis + ' ' + ket;
                if (!text.includes(searchQuery)) {
                    show = false;
                }
            }

            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Show/hide empty message
        const emptyRow = document.querySelector('#layananTable tbody tr.empty-row');
        if (emptyRow) {
            emptyRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    // Tab click handlers
    document.querySelectorAll('#layananTabs .nav-link').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            document.querySelectorAll('#layananTabs .nav-link').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Set filter and apply
            currentLayananFilter = this.dataset.filter || '';
            applyFilters();
        });
    });

    // Filter event listeners
    document.getElementById('filterStatus').addEventListener('change', applyFilters);
    document.getElementById('filterBulan').addEventListener('change', applyFilters);
    document.getElementById('filterTahun').addEventListener('change', applyFilters);
    document.getElementById('searchInput').addEventListener('keyup', applyFilters);

    console.log('Dashboard filters initialized');
});
</script>
@endsection