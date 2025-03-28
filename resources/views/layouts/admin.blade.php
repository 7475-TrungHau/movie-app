<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white fixed h-full shadow-lg transition-all duration-300">
            <div class="p-6 border-b border-gray-800">
                <h1 class="text-2xl font-bold tracking-tight">Admin Panel</h1>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center py-3 px-6 text-gray-200 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href=""
                    class="flex items-center py-3 px-6 text-gray-200 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>
                <a href=""
                    class="flex items-center py-3 px-6 text-gray-200 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.movies.*') ? 'bg-gray-800 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
                    </svg>
                    Movies
                </a>
                <a href="{{ route('admin.category.index') }}"
                    class="flex items-center py-3 px-6 text-gray-200 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.category.*') ? 'bg-gray-800 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Category
                </a>
                <a href=""
                    class="flex items-center py-3 px-6 text-gray-200 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.billing.packages.*') ? 'bg-gray-800 text-white' : '' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18M9 3v18m6-18v18m-9-6h6"></path>
                    </svg>
                    Billing
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-white shadow fixed top-0 left-64 right-0 z-10">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <!-- User Profile Dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-3 focus:outline-none">
                            <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/40' }}"
                                alt="User Avatar"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                            <span class="text-gray-700 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <!-- Dropdown Menu -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-20">
                            <a href=""
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Edit Profile</a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="mt-20 p-6">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Script cho Dropdown -->
    <script>
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        userMenuButton.addEventListener('click', () => {
            userMenu.classList.toggle('hidden');
        });

        // Đóng menu khi click ra ngoài
        document.addEventListener('click', (event) => {
            if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>

    @yield('scripts')
</body>

</html>