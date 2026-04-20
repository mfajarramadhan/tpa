import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

/**
 * ===============================
 * GLOBAL STATE & HELPERS
 * ===============================
 */

const rootHtml = document.documentElement;

function getCssVar(name) {
    return getComputedStyle(rootHtml).getPropertyValue(name).trim();
}

/**
 * ===============================
 * THEME (DARK / LIGHT)
 * ===============================
 */

window.toggleTheme = function () {
    rootHtml.classList.toggle('dark');

    const isDark = rootHtml.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    updateThemeIcon(isDark);
    updateChartsTheme();
};

function updateThemeIcon(isDark) {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;

    btn.innerHTML = isDark
        ? '<iconify-icon icon="solar:sun-bold-duotone" width="22" class="text-[#FFC107]"></iconify-icon>'
        : '<iconify-icon icon="solar:moon-bold-duotone" width="22" class="text-[var(--text-main)]"></iconify-icon>';
}

// init theme saat load
(function initTheme() {
    const isDark =
        localStorage.getItem('theme') === 'dark' ||
        (!('theme' in localStorage) &&
            window.matchMedia('(prefers-color-scheme: dark)').matches);

    if (isDark) rootHtml.classList.add('dark');

    updateThemeIcon(isDark);
})();

/**
 * ===============================
 * SIDEBAR (MOBILE)
 * ===============================
 */

window.toggleSidebar = function () {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');

    if (!sidebar || !overlay) return;

    const isClosed = sidebar.classList.contains('-translate-x-full');

    if (isClosed) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
};

/**
 * ===============================
 * USER DROPDOWN
 * ===============================
 */

window.toggleUserDropdown = function () {
    const dropdown = document.getElementById('userDropdown');
    if (!dropdown) return;

    dropdown.classList.toggle('hidden');
};

/**
 * ===============================
 * SIMPLE SPA TAB (Dashboard UI)
 * ===============================
 */

const views = ['dashboard', 'pendaftar', 'verifikasi'];

const titles = {
    dashboard: 'Dashboard Utama',
    pendaftar: 'Manajemen Pendaftar',
    verifikasi: 'Verifikasi Berkas',
};

window.switchTab = function (tabId) {
    views.forEach((view) => {
        const viewEl = document.getElementById('view-' + view);
        const navEl = document.getElementById('nav-' + view);

        if (viewEl) viewEl.classList.remove('active');
        if (navEl) navEl.classList.remove('active');
    });

    const activeView = document.getElementById('view-' + tabId);
    const activeNav = document.getElementById('nav-' + tabId);
    const headerTitle = document.getElementById('header-title');

    if (activeView) activeView.classList.add('active');
    if (activeNav) activeNav.classList.add('active');
    if (headerTitle) headerTitle.textContent = titles[tabId];

    // close sidebar mobile
    if (window.innerWidth < 768) {
        toggleSidebar();
    }

    const scrollArea = document.getElementById('main-scroll-area');
    if (scrollArea) scrollArea.scrollTop = 0;

    // resize chart
    if (tabId === 'dashboard') {
        setTimeout(() => {
            if (window.chart1) window.chart1.resize();
            if (window.chart2) window.chart2.resize();
        }, 50);
    }
};

/**
 * ===============================
 * CHART.JS
 * ===============================
 */

let chart1 = null;
let chart2 = null;

function initCharts() {
    const canvasTren = document.getElementById('chartTren');
    const canvasStatus = document.getElementById('chartStatus');

    if (!canvasTren || !canvasStatus) return;

    const isDark = rootHtml.classList.contains('dark');

    const primaryColor = isDark ? '#634bea' : '#190182';
    const gridColor = isDark ? '#2A3645' : '#E9ECEF';
    const textColor = isDark ? '#94A3B8' : '#6C757D';

    const areaBgColor = isDark
        ? 'rgba(99, 75, 234, 0.1)'
        : 'rgba(25, 1, 130, 0.08)';

    /**
     * LINE CHART
     */
    chart1 = new Chart(canvasTren.getContext('2d'), {
        type: 'line',
        data: {
            labels: ['1 Mar', '5 Mar', '10 Mar', '15 Mar', '20 Mar', '25 Mar', '30 Mar'],
            datasets: [{
                data: [120, 190, 250, 210, 380, 420, 560],
                borderColor: primaryColor,
                backgroundColor: areaBgColor,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    grid: { color: gridColor },
                    ticks: { color: textColor }
                },
                x: {
                    ticks: { color: textColor }
                }
            }
        }
    });

    /**
     * DONUT CHART
     */
    chart2 = new Chart(canvasStatus.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Diterima', 'Cadangan', 'Ditolak'],
            datasets: [{
                data: [320, 80, 120],
                backgroundColor: ['#28A745', '#6F42C1', '#DC3545'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: { legend: { display: false } }
        }
    });

    // expose global (biar bisa resize)
    window.chart1 = chart1;
    window.chart2 = chart2;
}

function updateChartsTheme() {
    if (chart1) chart1.destroy();
    if (chart2) chart2.destroy();

    initCharts();
}

/**
 * ===============================
 * INIT
 * ===============================
 */

window.addEventListener('load', () => {
    initCharts();
});