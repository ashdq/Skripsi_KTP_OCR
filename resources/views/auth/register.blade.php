<x-guest-layout>
    <div class="auth-card-header">
        <h1 class="auth-card-title">Buat Akun Baru</h1>
        <p class="auth-card-subtitle">Daftarkan diri Anda untuk mengakses layanan kelurahan secara digital</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nama Lengkap --}}
        <div class="form-group">
            <label class="form-label" for="nama">
                <i class="fas fa-user" style="margin-right:.35rem; color:#4caf78;"></i>Nama Lengkap
            </label>
            <div class="form-input-icon-wrap">
                <i class="fas fa-user form-input-icon"></i>
                <input
                    id="nama"
                    name="nama"
                    type="text"
                    class="form-input"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama lengkap"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>
            @error('nama')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Nomor HP --}}
        <div class="form-group">
            <label class="form-label" for="nomor_hp">
                <i class="fas fa-phone" style="margin-right:.35rem; color:#4caf78;"></i>Nomor HP
            </label>
            <div class="form-input-icon-wrap">
                <i class="fas fa-mobile-alt form-input-icon"></i>
                <input
                    id="nomor_hp"
                    name="nomor_hp"
                    type="tel"
                    class="form-input"
                    value="{{ old('nomor_hp') }}"
                    placeholder="08xxxxxxxxxx"
                    required
                    autocomplete="tel"
                >
            </div>
            @error('nomor_hp')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

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
                    autocomplete="username"
                >
            </div>
            @error('email')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <label class="form-label" for="password">
                <i class="fas fa-lock" style="margin-right:.35rem; color:#4caf78;"></i>Password
            </label>
            <div class="form-input-icon-wrap">
                <i class="fas fa-lock form-input-icon"></i>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="form-input"
                    placeholder="Minimal 8 karakter"
                    required
                    autocomplete="new-password"
                >
            </div>
            @error('password')
                <p class="form-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-auth-primary" id="btn-register">
            <i class="fas fa-user-plus"></i> Buat Akun Sekarang
        </button>
    </form>

    <div class="auth-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
</x-guest-layout>
