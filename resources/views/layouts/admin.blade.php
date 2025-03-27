<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>

<body class="bg-gray-100 font-sans">
    <!-- Sidebar -->
    <div class="flex">
        <aside class="w-64 bg-gray-800 text-white h-screen fixed">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Admin Panel</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">Dashboard</a>
                <a href="" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">Users</a>
                <a href="" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.movies.*') ? 'bg-gray-700' : '' }}">Movies</a>
                <a href="" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.genres.*') ? 'bg-gray-700' : '' }}">Genres</a>
                <a href="" class="block py-2 px-4 hover:bg-gray-700 {{ request()->routeIs('admin.billing.packages.*') ? 'bg-gray-700' : '' }}">Billing</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="block w-full text-left py-2 px-4 hover:bg-red-700 bg-red-600">Logout</button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-6">
            <header class="bg-white shadow p-4 mb-6">
                <h2 class="text-xl font-semibold">@yield('page-title')</h2>
            </header>
            <div class="bg-white p-6 rounded-lg shadow">
                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>

</html>