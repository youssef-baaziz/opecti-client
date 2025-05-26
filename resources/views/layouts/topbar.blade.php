
<header class="d-flex align-items-center bg-light shadow-sm p-3" style="width: calc(100% - 365px); position: fixed; top: 0; z-index: 1000; left: 365px;">
    <div class="d-flex justify-content-end col dropdown">
        <div class="d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
            <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
            <img src="{{ asset('images/profile.png') }}" alt="Profile Picture" class="rounded-circle ms-3" width="40" height="40">
        </div>
        <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb4f4M6X4k2z5h4g6z4t5h4g6z4t5h4g6z4t5h4g6z4t5h4g6" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9fX0r7x4f7h4g6z4t5h4g6z4t5h4g6z4t5h4g6z4t5h4g6z4t5h4g6" crossorigin="anonymous"></script>