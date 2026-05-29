<header class="app-header">
    <div class="header-content">
        <div class="header-left">
            <div class="logo-area">
                <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span class="logo-text">Sistem Informasi Kelurahan</span>
            </div>
        </div>
        <div class="header-right">
            @if (auth()->check())
                <button class="user-btn" id="profileBtn" type="button" aria-label="Buka menu pengguna">
                    <svg class="user-icon" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M12 14c-6 0-8 3-8 3v8h16v-8s-2-3-8-3z"></path>
                    </svg>
                </button>
                
                <!-- Profile Menu Popup -->
                <div class="profile-menu" id="profileMenu">
                    <a href="{{ route('profile.edit') }}" class="menu-item">
                        <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="menu-form">
                        @csrf
                        <button type="submit" class="menu-item logout-btn">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <a class="user-btn" href="{{ route('login') }}" aria-label="Masuk">
                    <svg class="user-icon" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M12 14c-6 0-8 3-8 3v8h16v-8s-2-3-8-3z"></path>
                    </svg>
                </a>
            @endif
        </div>
    </div>
</header>
