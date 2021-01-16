<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/img/logo2.png') }}" alt="STMIK Sumedang" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">E-Legalisir</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image">
                @php
                    $foto = auth()->user()->role == 'admin' ? asset('images/admin/'.auth()->user()->foto) : asset('images/alumni/'.auth()->user()->foto);
                @endphp
                <div class="profile-sidebar" style="width: 40px; height: 40px; background-image: url('{{ $foto }}'); border-radius: 100%; background-size: cover; background-position: center; background-repeat: no-repeat"></div>
            </div>
            <div class="info">
                <a href="{{ url('/profile') }}" class="d-block">{{ auth()->user()->role == 'admin' ? auth()->user()->admin->nama : auth()->user()->alumni->nama }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ Request::is('/') || (auth()->user()->role == 'alumni' && Request::is('permintaan-legalisir/*')) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if(auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ url('/permintaan-legalisir') }}" class="nav-link {{ Request::is('permintaan-legalisir') || Request::is('permintaan-legalisir/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-inbox"></i>
                            <p>Permintaan Legalisir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/legalisir') }}" class="nav-link {{ Request::is('legalisir') || Request::is('legalisir/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Riwayat Legalisir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/pelegalisir') }}" class="nav-link {{ Request::is('pelegalisir') || Request::is('pelegalisir/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>Pelegalisir</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/alumni') }}" class="nav-link {{ Request::is('alumni') || Request::is('alumni/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Alumni</p>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ url('/profile') }}" class="nav-link {{ Request::is('profile') || Request::is('profile/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profil Anda</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
