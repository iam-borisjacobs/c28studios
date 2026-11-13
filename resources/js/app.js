import './bootstrap';
import Alpine from 'alpinejs';

// Strictly isolated to public Blade layouts (never loaded on /admin/*)
if (!window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
