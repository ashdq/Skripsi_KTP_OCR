<aside class="app-sidebar">
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ url('/warga/home') }}" class="nav-link {{ request()->is('warga/home') ? 'active' : '' }}">
                    <i class="fas fa-home nav-icon"></i>
                    <span>Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/warga/pengurusan') }}" class="nav-link {{ request()->is('warga/pengurusan') ? 'active' : '' }}">
                    <i class="fas fa-file-contract nav-icon"></i>
                    <span>Pengurusan Surat</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/warga/unduh') }}" class="nav-link {{ request()->is('warga/unduh') ? 'active' : '' }}">
                    <i class="fas fa-download nav-icon"></i>
                    <span>Unduh Surat</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
