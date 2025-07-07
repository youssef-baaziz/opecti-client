@if(auth()->check())
    @if(auth()->user()->role === 'admin')
        @include('dashboard.admin')
    @elseif(auth()->user()->role === 'analyst')
        @include('dashboard.analyst')
    @elseif(auth()->user()->role === 'client')
        @include('dashboard.client')
    @endif
@endif
