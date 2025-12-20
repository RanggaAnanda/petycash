// public/js/layout.js

// Touch/Drag variables
let touchStartX = 0;
let touchStartY = 0;
let isDragging = false;
let currentTranslateX = 0;
let currentTranslateY = 0;
let swipeDirection = null;
let sidebar = null;

// Theme Toggle Function - EXPOSE TO WINDOW
window.toggleTheme = function() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
}

// Sidebar Toggle Function - EXPOSE TO WINDOW
window.toggleSidebar = function() {
    if (window.innerWidth >= 768) {
        sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.style.transform = 'translateX(0)';
        }
        return;
    }
    
    sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    if (!sidebar || !overlay) return; // Safety check
    
    sidebar.style.transition = 'transform 0.3s ease-in-out';
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
    
    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.style.transform = 'translateX(-100%)';
    } else {
        sidebar.style.transform = 'translateX(0) translateY(0)';
    }
    
    currentTranslateX = 0;
    currentTranslateY = 0;
    swipeDirection = null;
}

// Touch Event Handlers - EXPOSE TO WINDOW
window.handleTouchStart = function(e) {
    if (window.innerWidth >= 768) return;
    
    sidebar = document.getElementById('sidebar');
    if (!sidebar || sidebar.classList.contains('-translate-x-full')) return;
    if (e.target.closest('a, button')) return;
    
    touchStartX = e.touches[0].clientX;
    touchStartY = e.touches[0].clientY;
    isDragging = true;
    swipeDirection = null;
    sidebar.style.transition = 'none';
}

window.handleTouchMove = function(e) {
    if (!isDragging || window.innerWidth >= 768) return;
    if (e.target.closest('a, button')) {
        isDragging = false;
        return;
    }
    
    const touchEndX = e.touches[0].clientX;
    const deltaX = touchEndX - touchStartX;
    
    if (!swipeDirection && (Math.abs(deltaX) > 10 || Math.abs(deltaY) > 10)) {
        swipeDirection = Math.abs(deltaX) > Math.abs(deltaY) ? 'horizontal' : 'vertical';
    }
    
    if (swipeDirection === 'horizontal' && deltaX < 0) {
        currentTranslateX = Math.max(-100, (deltaX / sidebar.offsetWidth) * 100);
        sidebar.style.transform = `translateX(${currentTranslateX}%)`;
    }
}

window.handleTouchEnd = function(e) {
    if (!isDragging || window.innerWidth >= 768) return;
    
    isDragging = false;
    sidebar.style.transition = 'transform 0.3s ease-in-out';
    
    const threshold = 30;
    
    if (swipeDirection === 'horizontal' && currentTranslateX < -threshold){
        sidebar.classList.add('-translate-x-full');
        const overlay = document.getElementById('sidebar-overlay');
        if (overlay) overlay.classList.add('hidden');
        sidebar.style.transform = 'translateX(-100%)';
    } else {
        sidebar.style.transform = 'translateX(0) translateY(0)';
    }
    
    currentTranslateX = 0;
    currentTranslateY = 0;
    swipeDirection = null;
}

// FIX HEIGHT FOR MOBILE
window.fixMobileHeight = function() {
    if (window.innerWidth >= 768) return;
    
    // Set actual viewport height
    const vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
    
    // Adjust sidebar height
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.style.height = window.innerHeight + 'px';
    }
    
    // Adjust main content height
    const main = document.querySelector('main');
    const header = document.querySelector('header.md\\:hidden');
    if (main && header) {
        const headerHeight = header.offsetHeight;
        main.style.height = (window.innerHeight - headerHeight) + 'px';
    }
}

// Window Resize Handler
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.style.transform = 'translateX(0)';
        }
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }
    // Fix height on mobile resize
    fixMobileHeight();
});

// Keyboard Event Handler (ESC key to close sidebar)
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && window.innerWidth < 768) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
            window.toggleSidebar();
        }
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Fix mobile height
    fixMobileHeight();
    
    // Initialize sidebar for desktop
    if (window.innerWidth >= 768) {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if (sidebar) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.style.transform = 'translateX(0)';
        }
        if (overlay) {
            overlay.classList.add('hidden');
        }
    }
});

// Handle orientation change
window.addEventListener('orientationchange', function() {
    setTimeout(fixMobileHeight, 100);
});