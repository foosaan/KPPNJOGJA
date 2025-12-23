<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Logo_kementerian_keuangan_republik_indonesia.png/969px-Logo_kementerian_keuangan_republik_indonesia.png"
                alt="Logo" style="height: 40px; width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-3">KPPN Yogyakarta</div>
    </a>


    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('user.dashboard') }}">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Layanan
    </div>

    <!-- nav item kelola -->
    <li class="nav-item">
    <a class="nav-link" href="{{ route('vera.create') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Layanan Vera</span>
    </a>
</li>

    <li class="nav-item {{ request()->routeIs('bank.create') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('bank.create') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Layanan Bank</span>
    </a>
</li>

    <!-- mski -->
    <li class="nav-item {{ request()->routeIs('mski.create') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('mski.create') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Layanan MSKI</span>
    </a>
</li>

    <li class="nav-item {{ request()->routeIs('pd.create') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('pd.create') }}">
        <i class="fas fa-fw fa-users"></i>
        <span>Layanan PD</span>
    </a>
</li>
    
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>



</ul>