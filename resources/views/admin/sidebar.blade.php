<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_kementerian_keuangan_republik_indonesia.png/969px-Logo_kementerian_keuangan_republik_indonesia.png"
                alt="Logo" style="height: 40px; width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">KPPN Yogyakarta</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajement Pengguna
    </div>

    <!-- nav item kelola admin -->
    <li class="nav-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.admins.index') }}">
            <i class="fas fa-user-cog"></i>
            <span>Kelola Admin</span>
        </a>
    </li>

    <!-- staf -->
    <li class="nav-item {{ request()->routeIs('admin.staffs.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.staffs.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola Staf</span>
        </a>
    </li>

    <!-- user -->
    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Kelola User</span>
        </a>
    </li>

    <div class="sidebar-heading">
        Manajement Layanan
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item {{ request()->routeIs('admin.layanan.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.layanan.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Kelola Layanan</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>