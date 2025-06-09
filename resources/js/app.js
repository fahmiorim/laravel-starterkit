// Import CSS
import '../css/app.css';

// Add animation to auth container on page load
document.addEventListener('DOMContentLoaded', () => {
    // Add animation to auth container
    const authContainer = document.querySelector('.auth-container');
    if (authContainer) {
        setTimeout(() => {
            authContainer.classList.add('opacity-100', 'translate-y-0');
        }, 100);
    }

    // Mobile sidebar toggle
    const sidebarToggle = document.querySelector('[data-drawer-toggle="sidebar"]');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    // User Management Dropdown Toggle
    const userManagementBtn = document.getElementById('userManagementBtn');
    const userManagementMenu = document.getElementById('userManagementMenu');
    const dropdownIcon = document.getElementById('dropdownIcon');

    if (userManagementBtn && userManagementMenu) {
        // Toggle menu on button click
        userManagementBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event from bubbling up
            const isOpen = !userManagementMenu.classList.contains('hidden');
            
            // Toggle menu
            userManagementMenu.classList.toggle('hidden');
            
            // Toggle icon rotation
            if (dropdownIcon) {
                dropdownIcon.classList.toggle('rotate-180');
            }
            
            // Toggle button background
            this.classList.toggle('bg-gray-100', !isOpen);
            this.classList.toggle('dark:bg-gray-700', !isOpen);
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!userManagementBtn.contains(e.target) && !userManagementMenu.contains(e.target)) {
                userManagementMenu.classList.add('hidden');
                if (dropdownIcon) {
                    dropdownIcon.classList.remove('rotate-180');
                }
                userManagementBtn.classList.remove('bg-gray-100', 'dark:bg-gray-700');
            }
        });
        
        // Keep menu open when clicking on menu items
        userManagementMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Handle dropdown toggle
    document.addEventListener('click', function(e) {
        // Close all dropdowns when clicking outside
        if (!e.target.closest('.user-management-dropdown') && !e.target.closest('.user-management-menu')) {
            document.querySelectorAll('.user-management-dropdown').forEach(dropdown => {
                const menu = dropdown.nextElementSibling;
                const icon = dropdown.querySelector('svg');
                
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    dropdown.setAttribute('aria-expanded', 'false');
                    if (icon) icon.classList.remove('rotate-180');
                    dropdown.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                }
            });
            return;
        }

        // Handle dropdown button click
        const dropdownButton = e.target.closest('.user-management-dropdown');
        if (dropdownButton) {
            e.preventDefault();
            e.stopPropagation();
            
            const menu = dropdownButton.nextElementSibling;
            const icon = dropdownButton.querySelector('svg');
            const isExpanded = dropdownButton.getAttribute('aria-expanded') === 'true';
            
            // Close all other dropdowns first
            document.querySelectorAll('.user-management-dropdown').forEach(btn => {
                if (btn !== dropdownButton) {
                    const otherMenu = btn.nextElementSibling;
                    const otherIcon = btn.querySelector('svg');
                    if (otherMenu) otherMenu.classList.add('hidden');
                    btn.setAttribute('aria-expanded', 'false');
                    if (otherIcon) otherIcon.classList.remove('rotate-180');
                    btn.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                }
            });
            
            // Toggle current dropdown
            if (menu) {
                menu.classList.toggle('hidden');
                const newState = !isExpanded;
                dropdownButton.setAttribute('aria-expanded', newState);
                if (icon) icon.classList.toggle('rotate-180', newState);
                if (newState) {
                    dropdownButton.classList.add('bg-gray-100', 'dark:bg-gray-700');
                } else {
                    dropdownButton.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                }
            }
        }
    });
    
    // Auto-expand if current page is in the dropdown on page load
    document.querySelectorAll('.user-management-dropdown').forEach(button => {
        const currentLink = button.nextElementSibling?.querySelector('a[href="' + window.location.pathname + '"]');
        if (currentLink) {
            const menu = button.nextElementSibling;
            const icon = button.querySelector('svg');
            if (menu) menu.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
            if (icon) icon.classList.add('rotate-180');
            button.classList.add('bg-gray-100', 'dark:bg-gray-700');
        }
    });
});

