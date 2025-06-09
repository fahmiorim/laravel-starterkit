// Dark mode toggle functionality
const themeToggleBtn = document.getElementById('theme-toggle');
const html = document.documentElement;

// Check for saved user preference, if any, on load
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    html.classList.add('dark');
    localStorage.theme = 'dark';
} else {
    html.classList.remove('dark');
    localStorage.theme = 'light';
}

// Toggle theme on button click
themeToggleBtn?.addEventListener('click', () => {
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        html.classList.add('dark');
        localStorage.theme = 'dark';
    }
});

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const userMenuButton = document.getElementById('user-menu-button');
    
    if (dropdown && userMenuButton) {
        if (!userMenuButton.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    }
});

// Toggle user dropdown
const userMenuButton = document.getElementById('user-menu-button');
const userDropdown = document.getElementById('user-dropdown');

if (userMenuButton && userDropdown) {
    userMenuButton.addEventListener('click', function(e) {
        e.stopPropagation(); // Mencegah event bubbling
        userDropdown.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add('hidden');
        }
    });
}

// Close sidebar on mobile when clicking outside
const sidebar = document.getElementById('sidebar');
const sidebarToggles = document.querySelectorAll('[data-drawer-toggle="sidebar"]');

sidebarToggles.forEach(toggle => {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
});

// Close sidebar when clicking on a nav item on mobile
const navItems = document.querySelectorAll('#sidebar a');
navItems.forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth < 768) { // Only for mobile
            sidebar.classList.add('-translate-x-full');
        }
    });
});