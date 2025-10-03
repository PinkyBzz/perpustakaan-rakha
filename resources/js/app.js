import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// SEMUA ANIMASI DINONAKTIFKAN UNTUK MENCEGAH BUTTON HILANG
// JavaScript animation code dihapus total untuk stabilitas
console.log('Animations disabled - UI stability mode active');
