@extends('staff.app')

@section('content')
    <div class="container-fluid px-4">
        <h2 class="mb-4">Berkas Masuk</h2>

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
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari No Berkas / Layanan">
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="berkasTable" style="min-width: 1000px;">
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
                                <tr data-status="{{ $request->status }}">
                                    <td>{{ $request->no_berkas }}</td>
                                    <td>{{ $request->user->name ?? '-' }}</td>
                                    <td>{{ $request->jenis_layanan }}</td>
                                    <td>{{ $request->keterangan ?? '-' }}</td>
                                    <td>
                                        @if($request->file_path)
                                            <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">
                                                Lihat File
                                            </a>
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
                                    <td>
                                        @if($request->status == 'baru')
                                            <form action="{{ route('staff.updateStatus', [$request->id, $request->layanan_type ?? 'generik']) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="diproses">
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-cog"></i> Proses
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-check-circle text-success"></i> Sudah Diproses
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data berkas masuk</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterStatus = document.getElementById('filterStatus');
            const searchInput = document.getElementById('searchInput');
            
            if (filterStatus) {
                filterStatus.addEventListener('change', filterTable);
            }
            
            if (searchInput) {
                // Gunakan 'input' event yang lebih responsif daripada 'keyup'
                searchInput.addEventListener('input', filterTable);
            }
            
            function filterTable() {
                const status = filterStatus ? filterStatus.value.toLowerCase() : '';
                const search = searchInput ? searchInput.value.toLowerCase() : '';
                const rows = document.querySelectorAll('#berkasTable tbody tr');

                rows.forEach(row => {
                    // Skip jika row adalah row "tidak ada data"
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }
                    
                    const rowStatus = row.dataset.status || '';
                    const text = row.textContent.toLowerCase();
                    
                    const matchStatus = !status || rowStatus === status;
                    const matchSearch = !search || text.includes(search);
                    
                    row.style.display = matchStatus && matchSearch ? '' : 'none';
                });
            }
        });
    </script>
@endsection