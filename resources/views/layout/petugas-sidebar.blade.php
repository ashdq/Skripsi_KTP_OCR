<aside class="app-sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ url('/petugas/home') }}" class="nav-link {{ request()->is('petugas/home') ? 'active' : '' }}">
                    <i class="fas fa-home nav-icon"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/petugas/daftar') }}" class="nav-link {{ request()->is('petugas/daftar') ? 'active' : '' }}">
                    <i class="fas fa-file-lines nav-icon"></i>
                    <span>Daftar Pengajuan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/petugas/tanda-tangan') }}" class="nav-link {{ request()->is('petugas/tanda-tangan') ? 'active' : '' }}">
                    <i class="fas fa-pen-fancy nav-icon"></i>
                    <span>Tanda Tangan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
