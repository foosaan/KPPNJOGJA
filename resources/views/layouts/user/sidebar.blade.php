<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('user.dashboard') }}">
        <div class="sidebar-brand-icon">
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

    <!-- Dynamic Divisi Menu -->
    @isset($divisis)
        @foreach($divisis as $divisi)
            <li class="nav-item {{ request()->is('user/layanan/' . $divisi->slug) || request()->is('user/layanan-' . strtolower($divisi->nama) . '/*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('layanan.generik.create', $divisi->slug) }}">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Layanan {{ $divisi->nama }}</span>
                </a>
            </li>
        @endforeach
    @endisset
    
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>



</ul>