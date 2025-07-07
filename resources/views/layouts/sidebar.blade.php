<!-- Start of Selection -->
<!-- <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar"> -->
<ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">LPML <sup>-_-</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    @if(Auth::check())
        <li class="nav-item">
            <a class="nav-link">
                <i class="fas fa-fw fa-user"></i>
                <span>{{ Auth::user()->role }}</span>
            </a>
        </li>
    @endif

    @if(Auth::user()->role == 'admin')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rapport.home') ? 'active' : '' }}" href="{{ route('rapport.home') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Nav Item - Client -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">
                <i class="fas fa-fw fa-user"></i>
                <span>Client</span>
            </a>
        </li>

        <!-- Nav Item - Users -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>
    @elseif(Auth::user()->role == 'analyst')
        <!-- Nav Item - Analyst -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('analyst.dashboard') ? 'active' : '' }}" href="{{ route('analyst.dashboard') }}">
                <i class="fas fa-fw fa-chart-line"></i>
                <span>Analyst</span>
            </a>
        </li>

        <!-- Nav Item - Rapport -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rapport.index') ? 'active' : '' }}" href="{{ route('rapport.index') }}">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Rapport</span>
            </a>
        </li>
    @elseif(Auth::user()->role == 'client')
        <!-- Nav Item - Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('client-dh') ? 'active' : '' }}" href="{{ route('client-dh') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard 1</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('rapport.home') ? 'active' : '' }}" href="{{ route('rapport.home') }}">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Rapport</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Users</span>
            </a>
        </li>
    @endif

    <!-- Divider -->
    <!-- <hr class="sidebar-divider d-none d-md-block"> -->

    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->

</ul>
<!-- End of Selection -->
