<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('staff.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_kementerian_keuangan_republik_indonesia.png/969px-Logo_kementerian_keuangan_republik_indonesia.png"
                alt="Logo" style="height: 40px; width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">KPPN Yogyakarta</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('staff.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajement Berkas
    </div>

    <li class="nav-item {{ request()->routeIs('staff.berkasmasuk') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('staff.berkasmasuk') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Berkas Masuk</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('staff.berkasproses') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('staff.berkasproses') }}">
            <i class="fas fa-fw fa-folder"></i>
            <span>Berkas di Proses</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('staff.berkasselesai') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('staff.berkasselesai') }}">
            <i class="fas fa-fw fa-check"></i>
            <span>Berkas Selesai</span>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('staff.berkasditolak') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('staff.berkasditolak') }}">
            <i class="fas fa-fw fa-times"></i>
            <span>Berkas Ditolak</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
