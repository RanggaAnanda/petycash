<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Planet Fashion')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    height: {
                        'screen-dvh': '100dvh',
                    }
                }
            }
        };
    </script>
    
    <style>
        @media (max-width: 767px) {
            .h-mobile-screen {
                height: 100vh !important;
                height: -webkit-fill-available !important;
            }
            
            body {
                overscroll-behavior-y: none;
                -webkit-overflow-scrolling: touch;
            }
            
            .sidebar-mobile {
                max-height: 100vh;
                max-height: -webkit-fill-available;
            }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

<div class="flex md:h-screen h-mobile-screen overflow-hidden">
    <header class="md:hidden fixed top-0 left-0 right-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-50 px-4 py-3 flex items-center justify-between shadow-sm h-16">
        <div class="flex items-center">
            <button
                id="mobile-menu-btn"
                class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors mr-3"
                onclick="toggleSidebar()"
                aria-label="Toggle menu"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="flex items-center space-x-2">
            <button
                onclick="toggleTheme()"
                class="p-2 text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                aria-label="Toggle theme"
            >
                <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
            <img src="https://ui-avatars.com/api/?name=Tom+Cook&background=6366f1&color=fff" 
                 alt="User" 
                 class="w-8 h-8 rounded-full">
        </div>
    </header>

    <div
        id="sidebar-overlay"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"
        onclick="toggleSidebar()"
    ></div>

    @include('layouts.navigation')

    <div class="flex-1 flex flex-col md:h-screen h-mobile-screen overflow-hidden">
        <div class="md:hidden h-16 flex-shrink-0"></div>
        <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
            <div class="p-4 md:p-6">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/layout.js') }}"></script>
<script src="{{ asset('js/rupiah.js') }}"></script>

@stack('scripts')

</body>
</html>