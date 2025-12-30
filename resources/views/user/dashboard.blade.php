@extends('user.app')

@section('content')
    <div class="container">
        <!-- User Profile Section - Compact Vertical Layout -->
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
                <input type="text" id="searchInput" class="form-control" placeholder="Cari No Berkas / layanan / Keterangan">
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="layananTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="semua-tab" data-toggle="tab" href="#semua"
                            role="tab">Semua</a></li>
                    <li class="nav-item"><a class="nav-link" id="vera-tab" data-toggle="tab" href="#vera" role="tab">Layanan
                            Vera</a></li>
                    <li class="nav-item"><a class="nav-link" id="pd-tab" data-toggle="tab" href="#pd" role="tab">Layanan
                            PD</a></li>
                    <li class="nav-item"><a class="nav-link" id="mski-tab" data-toggle="tab" href="#mski" role="tab">Layanan
                            MSKI</a></li>
                    <li class="nav-item"><a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">Layanan
                            Bank</a></li>
                    <li class="nav-item"><a class="nav-link" id="umum-tab" data-toggle="tab" href="#umum" role="tab">Layanan
                            Umum</a></li>
                </ul>

                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="layananTabsContent">
                    <!-- Tab Semua -->
                    <div class="tab-pane fade show active" id="semua" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->layanan_type . ' - ' . $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->status ? ucfirst($request->status) : '-' }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                            <td>{{ $request->feedback ?? '-' }}</td>
                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data layanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Vera -->
                    <div class="tab-pane fade" id="vera" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                    @forelse($veraRequests as $request)
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->status ? ucfirst($request->status) : '-' }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                            <td>{{ $request->feedback ?? '-' }}</td>
                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data layanan Vera</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab PD -->
                    <div class="tab-pane fade" id="pd" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                    @forelse($pdRequests as $request)
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->status ? ucfirst($request->status) : '-' }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                            <td>{{ $request->feedback ?? '-' }}</td>
                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data layanan PD</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab MSKI -->
                    <div class="tab-pane fade" id="mski" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                    @forelse($mskiRequests as $request)
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->status ? ucfirst($request->status) : '-' }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                            <td>{{ $request->feedback ?? '-' }}</td>
                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data layanan MSKI</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Bank -->
                    <div class="tab-pane fade" id="bank" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                    @forelse($bankRequests as $request)
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>
                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>
                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ $request->status ? ucfirst($request->status) : '-' }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>
                                            <td>{{ $request->feedback ?? '-' }}</td>
                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data layanan Bank</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="umum">
                        <div class="table-responsive">
                            <table class="table table-striped filterable-table">
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
                                    @forelse($umumRequests as $request)
                                        <tr>
                                            <td>{{ $request->no_berkas }}</td>
                                            <td>{{ $request->jenis_layanan }}</td>
                                            <td>{{ $request->keterangan ?? '-' }}</td>

                                            <td>
                                                @if($request->file_path)
                                                    <a href="{{ asset('storage/' . $request->file_path) }}" target="_blank">Lihat
                                                        File</a>
                                                @else - @endif
                                            </td>

                                            <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                            <td>{{ ucfirst($request->status ?? '-') }}</td>
                                            <td>{{ $request->alasan_penolakan ?? '-' }}</td>

                                            <td>{{ $request->feedback ?? '-' }}</td>

                                            <td>
                                                @if($request->feedback_file)
                                                    <a href="{{ asset('storage/' . $request->feedback_file) }}"
                                                        target="_blank">Download</a>
                                                @else - @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Tidak ada data layanan Umum</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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

            document.querySelectorAll('.filterable-table tbody tr').forEach(row => {
                const noBerkas = row.cells[0]?.textContent.trim().toLowerCase();
                const jenis = row.cells[1]?.textContent.trim().toLowerCase();
                const ket = row.cells[2]?.textContent.trim().toLowerCase();
                const file = row.cells[3]?.textContent.trim().toLowerCase();
                const tanggal = row.cells[4]?.textContent.trim();
                const status = row.cells[5]?.textContent.trim().toLowerCase();

                let show = true;

                // Filter status
                if (statusFilter && status !== statusFilter) {
                    show = false;
                }

                // Filter bulan & tahun
                if (tanggal) {
                    const parts = tanggal.split(' ')[0].split('/'); // dd/mm/yyyy
                    const bulan = parts[1];
                    const tahun = parts[2];

                    if (bulanFilter && bulan !== bulanFilter) {
                        show = false;
                    }
                    if (tahunFilter && tahun !== tahunFilter) {
                        show = false;
                    }
                }

                // Filter search (no berkas, keterangan, file, jenis)
                if (searchQuery) {
                    const text = (noBerkas + ' ' + ket + ' ' + file + ' ' + jenis);
                    if (!text.includes(searchQuery)) {
                        show = false;
                    }
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