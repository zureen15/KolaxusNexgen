document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar');
    const themeSwitch = document.getElementById('theme-switch');
    const desktopProfile = document.getElementById('desktop-profile');
    const profileDropdown = document.querySelector('.profile-dropdown');

    // Check if we're on mobile
    const isMobile = window.innerWidth < 992;

    // Flag to prevent multiple toggle actions
    let isToggling = false;

    // Function to toggle mobile sidebar
    function toggleMobileSidebar() {
        if (isToggling) return;
        isToggling = true;

        mobileSidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');

        // Prevent body scrolling when sidebar is open
        if (mobileSidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }

        // Reset toggle flag after animation completes
        setTimeout(() => {
            isToggling = false;
        }, 300);
    }

    // Function to close mobile sidebar
    function closeMobileSidebar() {
        if (mobileSidebar.classList.contains('active')) {
            toggleMobileSidebar();
        }
    }

    // Apply saved theme
    function applyTheme() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark');
            if (themeSwitch) themeSwitch.checked = true;
        } else {
            document.body.classList.remove('dark');
            if (themeSwitch) themeSwitch.checked = false;
        }
    }

    // Initialize theme
    applyTheme();

    // Event Listeners
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', toggleMobileSidebar);
    }

    if (closeSidebarBtn) {
        closeSidebarBtn.addEventListener('click', closeMobileSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }

    if (themeSwitch) {
        themeSwitch.addEventListener('change', function () {
            if (this.checked) {
                document.body.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });
    }

    // Desktop profile dropdown toggle
    if (desktopProfile && profileDropdown) {
        desktopProfile.addEventListener('click', function (e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function () {
            profileDropdown.classList.remove('active');
        });

        // Prevent closing when clicking inside dropdown
        if (profileDropdown) {
            profileDropdown.addEventListener('click', function (e) {
                e.stopPropagation();
            });
        }
    }

    // Close sidebar when clicking on a link (mobile only)
    const sidebarLinks = document.querySelectorAll('#mobile-sidebar a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function () {
            if (isMobile) {
                closeMobileSidebar();
            }
        });
    });

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            const newIsMobile = window.innerWidth < 992;
            if (newIsMobile !== isMobile && newIsMobile === false) {
                // Close mobile sidebar when switching to desktop
                closeMobileSidebar();
            }
        }, 250);
    });
});