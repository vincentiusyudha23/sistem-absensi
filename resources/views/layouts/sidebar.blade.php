<div class="sidebar bg-light bg-light-subtle border-end p-3 d-flex flex-column">

    @php
        $route_prefix = request()->segment(1) == 'admin' ? 'admin.' : 'user.';
    @endphp

    <div>
        <h6 class="text-success fw-bold mb-3">
            MENU
        </h6>

        <ul class="nav nav-pills flex-column gap-1">

            <li>
                <a href="{{ route($route_prefix . 'dashboard') }}" class="nav-link {{ request()->routeIs( $route_prefix . 'dashboard') ? 'active' : 'text-dark' }}">
                    <i class="bi bi-speedometer"></i> Dashboard
                </a>
            </li>

            @role('admin')
                <li>
                    <a href="{{ route('admin.list_users') }}" class="nav-link {{ request()->routeIs('admin.list_users') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-people-fill"></i> Daftar Anggota
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.data_absensi') }}" class="nav-link {{ request()->routeIs('admin.data_absensi') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-file-earmark-text-fill"></i> Data Absensi
                    </a>
                </li>

                <li>
                    <a href="#" class="nav-link text-dark">
                        <i class="bi bi-gear-fill"></i> Pengaturan
                    </a>
                </li>
            @else
                <li>
                    <a href="{{ route('user.riwayat_absen') }}" class="nav-link {{ request()->routeIs('user.riwayat_absen') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-file-earmark-text-fill"></i> Riwayat Absensi
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.profile') }}" class="nav-link {{ request()->routeIs('user.profile') ? 'active' : 'text-dark' }}">
                        <i class="bi bi-person-fill"></i> Profil
                    </a>
                </li>
            @endrole

        </ul>

        <hr>

    </div>

    <!-- BAGIAN BAWAH -->
    <div class="mt-auto text-center">
        <img src="/assets/img/male.png" alt="user" width="50" height="50">
        <h5 class="fw-bold fs-5 mt-2">{{ Auth::user()->name }}</h5>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger fw-semibold">
                <i class="bi bi-box-arrow-left"></i>
                Logout
            </button>
        </form>
    </div>

</div>