<aside class="bg-light border-end" id="sidebar-wrapper" style="width: 365px; height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto;">
    <div class="sidebar-heading text-center pt-4 mb-2 fs-4 fw-bold">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid" style="max-width: 140px;">
    </div>
    <nav class="list-group list-group-flush">
        @if(Auth::user()->role == 'superadmin')
            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-house-door me-2"></i> Acceil
            </a>
            <a href="{{ route('client.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-person-lines-fill me-2"></i> Client
            </a>
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-person me-2"></i> Admin
            </a>
            <a href="{{ route('analyste.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-graph-down me-2"></i> Analyste
            </a>
            <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-person me-2"></i> Users
            </a>
            <a href="{{ route('rapport.home') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-house-door me-2"></i> Rapport
            </a>
        @elseif(Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-house-door me-2"></i> Acceil
            </a>
        @elseif(Auth::user()->role == 'client')
            <a href="{{ route('client.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center {{ request()->routeIs('client.dashboard') ? 'text-weight-bold' : '' }}">
                <i class="bi bi-house-door me-2"></i> Acceil
            </a>
            <a href="{{ route('client.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-graph-down me-2"></i> dashboard
            </a>
        @elseif(Auth::user()->role == 'analyste')
            <a href="{{ route('analyste.dashboard') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-graph-down me-2"></i> Acceil
            </a>
            <a href="{{ route('rapports.index') }}" class="list-group-item list-group-item-action bg-light d-flex align-items-center">
                <i class="bi bi-house-door me-2"></i> Rapport
            </a>
        @endif
    </nav>
</aside>