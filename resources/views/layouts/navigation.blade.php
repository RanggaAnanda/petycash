<!-- Sidebar -->
<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 h-[calc(100vh)] md:h-screen transform -translate-x-full md:translate-x-0 transition-all duration-300 ease-in-out z-50 flex flex-col shadow-lg md:shadow-none sidebar-mobile"
    ontouchstart="handleTouchStart(event)" ontouchmove="handleTouchMove(event)" ontouchend="handleTouchEnd(event)">
    <!-- Logo -->
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="w-10 h-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 20C8 20 12 12 20 12C28 12 32 20 32 20C32 20 28 28 20 28C12 28 8 20 8 20Z"
                        fill="#6366f1" />
                    <path d="M12 20C12 20 14 16 18 16C22 16 24 20 24 20C24 20 22 24 18 24C14 24 12 20 12 20Z"
                        fill="#818cf8" />
                </svg>
                <span class="text-xl font-bold text-gray-900 dark:text-white">Planet Fashion</span>
            </div>
            <button onclick="toggleSidebar()"
                class="md:hidden text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors p-1"
                aria-label="Close menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Theme Toggle Button (Desktop) -->
    <div class="hidden md:block px-4 pt-4">
        <button onclick="toggleTheme()"
            class="w-full flex items-center justify-center px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
            aria-label="Toggle theme">
            <svg class="w-5 h-5 mr-2 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
            </svg>
            <svg class="w-5 h-5 mr-2 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <span class="text-sm font-medium hidden dark:block">Light Mode</span>
            <span class="text-sm font-medium block dark:hidden">Dark Mode</span>
        </button>
    </div>

    <!-- Menu - Tambah overflow-y-auto -->
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <x-nav.button route="dashboard"
            icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
            value="Dashboard" />

        <!-- Form Dropdown -->
        <div>
            <x-nav.dropdown label="Form"
                icon="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                prefix="form.*" :items="[
                    ['label' => 'Uang Masuk', 'route' => 'forms.uang-masuk.create'],
                    ['label' => 'Uang Keluar', 'route' => 'forms.uang-keluar.create'],
                    ['label' => 'Omset', 'route' => 'forms.omset.create'],
                ]" />
        </div>

        <!-- Daftar Dropdown -->
        <div>
            <x-nav.dropdown label="Daftar"
                icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                prefix="Dafter.*" :items="[
                    ['label' => 'Petty Cash', 'route' => 'daftar.petycash.index'],
                    ['label' => 'Omset', 'route' => 'daftar.omset.index'],
                ]" />
        </div>

        <!-- Master Dropdown -->
        <div>
            <x-nav.dropdown label="Master"
                icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                prefix="master.*" :items="[
                    ['label' => 'User', 'route' => 'master.users.index'],
                    ['label' => 'Divisi/Toko', 'route' => 'master.stores.index'],
                    ['label' => 'Kategori', 'route' => 'master.kategori.index'],
                    ['label' => 'Vendor', 'route' => 'master.vendors.index'],
                    ['label' => 'Account', 'route' => 'master.accounts.index'],
                ]" />
        </div>

        <!-- Laporan Dropdown -->
        <div>
            <x-nav.dropdown label="Laporan"
                icon="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                prefix="Laporan." :items="[
                    ['label' => 'Petty Cash', 'route' => 'laporan.petycash'],
                    ['label' => 'Omset', 'route' => 'laporan.omset'],
                ]" />
        </div>
    </nav>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto relative">
        <div id="profileBtn"
            class="flex items-center px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
            <img src="https://ui-avatars.com/api/?name=Tom+Cook&background=6366f1&color=fff" alt="Rangga Ananda"
                class="w-10 h-10 rounded-full mr-3">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Rangga Ananda</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">rangga@test</p>
            </div>
            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>

        <!-- Dropdown Menu -->
        <div id="profileDropdown"
            class="absolute bottom-full left-0 mb-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hidden z-50">
            <a href="{{ route('profile') }}"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
            <a href="{{ route('login') }}"
                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-400">Logout</a>
        </div>
    </div>
</aside>

<script>
    function toggleDropdown(menu) {
        const dropdown = document.getElementById(menu + '-dropdown');
        const arrow = document.getElementById(menu + '-arrow');

        dropdown.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');
    }

    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    profileBtn.addEventListener('click', () => {
        profileDropdown.classList.toggle('hidden');
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener('click', (e) => {
        if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
</script>
