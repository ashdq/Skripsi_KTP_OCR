import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Profile Menu Popup
document.addEventListener('DOMContentLoaded', function() {
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    if (!profileBtn || !profileMenu) return;

    // Toggle menu saat button diklik
    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        profileMenu.classList.toggle('show');
    });

    // Tutup menu saat klik di luar
    document.addEventListener('click', function(e) {
        if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.remove('show');
        }
    });

    // Tutup menu saat ESC ditekan
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            profileMenu.classList.remove('show');
        }
    });

    // Tutup menu saat item diklik
    const menuItems = profileMenu.querySelectorAll('.menu-item, .logout-btn');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            profileMenu.classList.remove('show');
        });
    });
});
