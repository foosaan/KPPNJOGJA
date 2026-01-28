@extends('layouts.admin.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cogs"></i> Kelola Divisi & Layanan
        </h1>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Layanan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-list fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Divisi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_types'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-layer-group fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Layanan Aktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Layanan Nonaktif</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['inactive'] }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-times fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Tabs -->
    @php $activeTab = request('tab', 'divisi'); @endphp
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'divisi' ? 'active' : '' }}" id="divisi-tab" data-toggle="tab" href="#divisiTab" role="tab">
                        <i class="fas fa-building"></i> Kelola Divisi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab == 'layanan' ? 'active' : '' }}" id="layanan-tab" data-toggle="tab" href="#layananTab" role="tab">
                        <i class="fas fa-list-alt"></i> Kelola Layanan
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="mainTabsContent">
                
                {{-- TAB DIVISI --}}
                <div class="tab-pane fade {{ $activeTab == 'divisi' ? 'show active' : '' }}" id="divisiTab" role="tabpanel">
                    <div class="row">
                        <!-- Form Tambah Divisi -->
                        <div class="col-md-4">
                            <div class="card border-left-primary mb-4">
                                <div class="card-header bg-primary text-white">
                                    <i class="fas fa-plus"></i> Tambah Divisi Baru
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.divisi.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="font-weight-bold">Nama Divisi <span class="text-danger">*</span></label>
                                            <input type="text" name="nama" class="form-control" placeholder="Contoh: Kepegawaian" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <input type="text" name="deskripsi" class="form-control" placeholder="Opsional">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="divisiActive" name="is_active" checked>
                                                <label class="custom-control-label" for="divisiActive">Aktif</label>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fas fa-save"></i> Simpan Divisi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Divisi -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-list"></i> Daftar Divisi
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Nama Divisi</th>
                                                    <th>Deskripsi</th>
                                                    <th width="10%">Layanan</th>
                                                    <th width="10%">Status</th>
                                                    <th width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($divisis as $index => $divisi)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="font-weight-bold">{{ $divisi->nama }}</td>
                                                        <td class="text-muted small">{{ $divisi->deskripsi ?? '-' }}</td>
                                                        <td class="text-center">
                                                            <span class="badge badge-info">{{ $divisi->layanans->count() }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($divisi->is_active)
                                                                <button type="button" class="btn btn-sm btn-success toggle-divisi" 
                                                                    data-id="{{ $divisi->id }}" data-url="{{ route('admin.divisi.toggle', $divisi) }}" 
                                                                    title="Klik untuk nonaktifkan">
                                                                    <i class="fas fa-toggle-on"></i> Aktif
                                                                </button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-secondary toggle-divisi" 
                                                                    data-id="{{ $divisi->id }}" data-url="{{ route('admin.divisi.toggle', $divisi) }}" 
                                                                    title="Klik untuk aktifkan">
                                                                    <i class="fas fa-toggle-off"></i> Nonaktif
                                                                </button>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <!-- Edit Button -->
                                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editDivisiModal{{ $divisi->id }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <!-- Delete Button -->
                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteDivisiModal{{ $divisi->id }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <!-- Edit Modal -->
                                                    <div class="modal fade" id="editDivisiModal{{ $divisi->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <form action="{{ route('admin.divisi.update', $divisi) }}" method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-header bg-warning">
                                                                        <h5 class="modal-title">Edit Divisi</h5>
                                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label class="font-weight-bold">Nama Divisi</label>
                                                                            <input type="text" name="nama" class="form-control" value="{{ $divisi->nama }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Deskripsi</label>
                                                                            <input type="text" name="deskripsi" class="form-control" value="{{ $divisi->deskripsi }}">
                                                                        </div>
                                                                        <div class="custom-control custom-switch">
                                                                            <input type="checkbox" class="custom-control-input" id="editActive{{ $divisi->id }}" name="is_active" {{ $divisi->is_active ? 'checked' : '' }}>
                                                                            <label class="custom-control-label" for="editActive{{ $divisi->id }}">Aktif</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Delete Modal -->
                                                    <div class="modal fade" id="deleteDivisiModal{{ $divisi->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title">Hapus Divisi</h5>
                                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Yakin ingin menghapus divisi <strong>{{ $divisi->nama }}</strong>?</p>
                                                                    @if($divisi->layanans->count() > 0)
                                                                        <div class="alert alert-warning">
                                                                            <i class="fas fa-exclamation-triangle"></i> Divisi ini memiliki {{ $divisi->layanans->count() }} layanan. Hapus layanan terlebih dahulu.
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                    <form action="{{ route('admin.divisi.destroy', $divisi) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger" {{ $divisi->layanans->count() > 0 ? 'disabled' : '' }}>
                                                                            <i class="fas fa-trash"></i> Hapus
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4">
                                                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                                            <p class="text-muted">Belum ada divisi. Tambahkan divisi pertama di form sebelah kiri.</p>
                                                        </td>
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

                {{-- TAB LAYANAN --}}
                <div class="tab-pane fade {{ $activeTab == 'layanan' ? 'show active' : '' }}" id="layananTab" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0"><i class="fas fa-list-alt"></i> Daftar Layanan</h5>
                        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Layanan
                        </a>
                    </div>

                    <!-- Filter -->
                    <form method="GET" action="{{ route('admin.layanan.index') }}" class="mb-3" id="filterLayananForm">
                        <input type="hidden" name="tab" value="layanan">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="divisi_id" class="form-control form-control-sm" id="filterDivisi" onchange="document.getElementById('filterLayananForm').submit()">
                                    <option value="">Semua Divisi</option>
                                    @foreach($divisis as $divisi)
                                        <option value="{{ $divisi->id }}" {{ request('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                            {{ $divisi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control form-control-sm" id="filterStatus" onchange="document.getElementById('filterLayananForm').submit()">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request()->has('status') && request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request()->has('status') && request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control form-control-sm" id="filterSearch" placeholder="Cari layanan..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.layanan.index', ['tab' => 'layanan']) }}" class="btn btn-sm btn-secondary" title="Reset Filter"><i class="fas fa-redo"></i> Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Divisi</th>
                                    <th width="30%">Jenis Layanan</th>
                                    <th width="30%">Deskripsi</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($layanans as $index => $layanan)
                                    <tr>
                                        <td>{{ $layanans->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge badge-{{ $layanan->badge_color }} px-3 py-2">
                                                <i class="fas {{ $layanan->icon }}"></i>
                                                {{ $layanan->divisi->nama ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="font-weight-bold">{{ $layanan->jenis_layanan }}</td>
                                        <td class="small text-muted">{{ $layanan->deskripsi ?? '-' }}</td>
                                        <td class="text-center">
                                            @if($layanan->is_active)
                                                <button type="button" class="btn btn-sm btn-success toggle-layanan" 
                                                    data-id="{{ $layanan->id }}" data-url="{{ route('admin.layanan.toggle', $layanan) }}" 
                                                    title="Klik untuk nonaktifkan">
                                                    <i class="fas fa-toggle-on"></i> Aktif
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary toggle-layanan" 
                                                    data-id="{{ $layanan->id }}" data-url="{{ route('admin.layanan.toggle', $layanan) }}" 
                                                    title="Klik untuk aktifkan">
                                                    <i class="fas fa-toggle-off"></i> Nonaktif
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.layanan.edit', $layanan) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteLayananModal{{ $layanan->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Delete Layanan Modal -->
                                    <div class="modal fade" id="deleteLayananModal{{ $layanan->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">Hapus Layanan</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Yakin ingin menghapus layanan <strong>{{ $layanan->jenis_layanan }}</strong>?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    <form action="{{ route('admin.layanan.destroy', $layanan) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                            <p class="text-muted">Belum ada layanan</p>
                                            <a href="{{ route('admin.layanan.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i> Tambah Layanan
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Menampilkan {{ $layanans->firstItem() ?? 0 }} - {{ $layanans->lastItem() ?? 0 }} dari {{ $layanans->total() }} data
                        </div>
                        <div>{{ $layanans->appends(request()->query())->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
        setTimeout(function () { $('.alert').fadeOut('slow'); }, 5000);
        
        // Auto-activate tab based on URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        
        if (tabParam === 'layanan') {
            $('#layanan-tab').tab('show');
        }
        
        // Auto-submit search filter with debounce (500ms)
        let searchTimeout;
        $('#filterSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                $('#filterLayananForm').submit();
            }, 500);
        });
        
        // Toggle for Layanan - reload with tab param
        $(document).on('click', '.toggle-layanan', function() {
            const btn = $(this);
            const url = btn.data('url');
            
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> ...');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    // Reload dengan parameter tab=layanan agar tetap di tab Layanan
                    window.location.href = '{{ route("admin.layanan.index") }}?tab=layanan';
                },
                error: function() {
                    alert('Gagal mengubah status. Silakan coba lagi.');
                    btn.prop('disabled', false);
                    location.reload();
                }
            });
        });
        
        // Toggle for Divisi - reload halaman dengan tab=divisi
        $(document).on('click', '.toggle-divisi', function() {
            const btn = $(this);
            const url = btn.data('url');
            
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> ...');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    window.location.href = '{{ route("admin.layanan.index") }}?tab=divisi';
                },
                error: function() {
                    alert('Gagal mengubah status. Silakan coba lagi.');
                    btn.prop('disabled', false);
                    window.location.href = '{{ route("admin.layanan.index") }}?tab=divisi';
                }
            });
        });
    });
</script>
@endpush