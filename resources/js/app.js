import './bootstrap';
import Alpine from 'alpinejs';

// Inisialisasi Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Tema toggle
const themeToggleBtn = document.getElementById('theme-toggle');
const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

if (themeToggleDarkIcon && themeToggleLightIcon) {
    if (localStorage.getItem('theme') === 'dark') {
        themeToggleLightIcon.classList.remove('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
    }
}

const handleToggle = () => {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark') {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
        if (themeToggleDarkIcon && themeToggleLightIcon) {
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        }
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
        if (themeToggleDarkIcon && themeToggleLightIcon) {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        }
    }
}

if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', handleToggle);
}
