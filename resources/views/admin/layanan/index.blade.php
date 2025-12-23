@extends('admin.app')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-cogs"></i> Kelola Layanan
                @if(isset($filterType) && $filterType)
                        <span class="badge badge-{{ 
                                $filterType == 'Vera' ? 'info' :
                    ($filterType == 'PD' ? 'warning' :
                        ($filterType == 'MSKI' ? 'success' : 'primary')) 
                            }}">
                            {{ $filterType }}
                        </span>
                @endif
            </h1>
            @if(isset($filterType) && $filterType)
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-sm btn-secondary mt-2">
                    <i class="fas fa-times"></i> Hapus Filter
                </a>
            @endif
        </div>
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Layanan
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
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
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
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
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jenis Tipe</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_types'] }} Tipe</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter"></i> Filter & Pencarian
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.layanan.index') }}" id="filterForm">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="filterType" class="form-label small font-weight-bold">Tipe Layanan</label>
                    <input type="text" 
                           class="form-control form-control-sm" 
                           id="filterType" 
                           name="type"
                           placeholder="Ketik tipe layanan..."
                           value="{{ request('type') }}">
                    <small class="form-text text-muted">Contoh: Vera, Umum, Bank</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="filterStatus" class="form-label small font-weight-bold">Status</label>
                    <select class="form-control form-control-sm" id="filterStatus" name="status">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="searchInput" class="form-label small font-weight-bold">Cari Layanan</label>
                    <input type="text" class="form-control form-control-sm" id="searchInput" name="search"
                        placeholder="Cari jenis layanan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label small font-weight-bold">&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-sm btn-primary btn-block">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.layanan.index') }}" class="btn btn-sm btn-secondary btn-block">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
    <!-- DataTable -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Layanan</h6>
            <div>
                <span class="badge badge-info">Total: {{ $layanans->total() }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">Tipe Layanan</th>
                            <th width="30%">Jenis Layanan</th>
                            <th width="30%">Deskripsi</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($layanans as $index => $layanan)
                            <tr>
                                <td class="text-center">{{ $layanans->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge badge-{{ $layanan->badge_color }} px-3 py-2">
                                        <i class="fas {{ $layanan->icon }}"></i>
                                        {{ $layanan->layanan_type }}
                                    </span>
                                </td>
                                <td class="font-weight-bold">{{ $layanan->jenis_layanan }}</td>
                                <td class="small text-muted">
                                    {{ $layanan->deskripsi ? Str::limit($layanan->deskripsi, 100) : '-' }}
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.layanan.toggle', $layanan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @if($layanan->is_active)
                                            <button type="submit" class="badge badge-success border-0"
                                                onclick="return confirm('Nonaktifkan layanan ini?')" style="cursor: pointer;">
                                                <i class="fas fa-check"></i> Aktif
                                            </button>
                                        @else
                                            <button type="submit" class="badge badge-secondary border-0"
                                                onclick="return confirm('Aktifkan layanan ini?')" style="cursor: pointer;">
                                                <i class="fas fa-times"></i> Nonaktif
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.layanan.edit', $layanan) }}" class="btn btn-sm btn-warning"
                                            data-toggle="tooltip" title="Edit Layanan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-toggle="modal"
                                            data-target="#deleteModal{{ $layanan->id }}" title="Hapus Layanan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $layanan->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="fas fa-trash"></i> Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="close text-white" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus layanan ini?</p>
                                            <div class="alert alert-warning">
                                                <strong>Tipe:</strong> {{ $layanan->layanan_type }}<br>
                                                <strong>Jenis:</strong> {{ $layanan->jenis_layanan }}
                                            </div>
                                            <p class="text-danger mb-0">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Data yang dihapus tidak dapat dikembalikan!
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                            <form action="{{ route('admin.layanan.destroy', $layanan) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Ya, Hapus
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
                                    <p class="text-muted mb-0">Belum ada data layanan</p>
                                    <a href="{{ route('admin.layanan.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-plus"></i> Tambah Layanan Pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Menampilkan {{ $layanans->firstItem() ?? 0 }} sampai {{ $layanans->lastItem() ?? 0 }}
                    dari {{ $layanans->total() }} data
                </div>
                <div>
                    {{ $layanans->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Auto dismiss alert
            setTimeout(function () {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@endpush