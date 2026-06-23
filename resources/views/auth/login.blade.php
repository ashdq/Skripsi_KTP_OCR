<x-guest-layout>
    <div class="auth-card-header">
        <h1 class="auth-card-title">Selamat Datang</h1>
        <p class="auth-card-subtitle">Masuk ke portal layanan Kelurahan Digital</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="session-status">
            <i class="fas fa-check-circle" style="margin-right:.4rem;"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label" for="email">
                <i class="fas fa-envelope" style="margin-right:.35rem; color:#4caf78;"></i>Alamat Email
            </label>
            <div class="form-input-icon-wrap">
                <i class="fas fa-at form-input-icon"></i>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>
            @error('email')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.45rem;">
                <label class="form-label" for="password" style="margin-bottom:0;">
                    <i class="fas fa-lock" style="margin-right:.35rem; color:#4caf78;"></i>Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="auth-link">Lupa password?</a>
                @endif
            </div>
            <div class="form-input-icon-wrap">
                <i class="fas fa-lock form-input-icon"></i>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    placeholder="••••••••"
                    required
                    autocomplete="current-password"
                >
            </div>
            @error('password')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Remember Me --}}
        <label class="form-check" for="remember_me">
            <input id="remember_me" type="checkbox" name="remember">
            <span class="form-check-label">Ingat saya di perangkat ini</span>
        </label>

        <button type="submit" class="btn-auth-primary" id="btn-login">
            <i class="fas fa-sign-in-alt"></i> Masuk ke Portal
        </button>
    </form>

    @if (Route::has('register'))
        <div class="auth-footer">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    @endif
</x-guest-layout>
