@extends('layouts.admin')

@section('title', 'Movies')
@section('page-title', 'Manage Movies')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-semibold text-gray-800">Movies List</h3>
        <a href="{{ route('admin.movie.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Movie
        </a>
    </div>

    <!-- Notifications -->
    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
        {{ session('error') }}
    </div>
    @endif

    <!-- Search and Filters -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
        <form id="filterForm" method="GET" action="{{ route('admin.movie.index') }}" class="flex flex-col sm:flex-row gap-4 items-center w-full">
            @csrf
            <!-- Search Bar -->
            <div class="w-full sm:w-1/3">
                <input type="text" id="search" name="search" placeholder="Search movies by name..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                    value="{{ request('search') }}"
                    onkeyup="if(event.key === 'Enter') this.form.submit()">
            </div>

            <!-- Filters -->
            <select name="sort_by" onchange="this.form.submit()"
                class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <option value="id" {{ request('sort_by') === 'id' ? 'selected' : '' }}>Sort by ID</option>
                <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Sort by Name</option>
                <option value="category_id" {{ request('sort_by') === 'category_id' ? 'selected' : '' }}>Sort by Category</option>
                <option value="type" {{ request('sort_by') === 'type' ? 'selected' : '' }}>Sort by Type</option>
            </select>
            <select name="sort_direction" onchange="this.form.submit()"
                class="px-4 py-2 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <option value="asc" {{ request('sort_direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                <option value="desc" {{ request('sort_direction') === 'desc' ? 'selected' : '' }}>Descending</option>
            </select>
        </form>
    </div>

    <!-- Movies Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($movies as $movie)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movie->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movie->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movie->category->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst($movie->type) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.movie.edit', $movie) }}"
                                class="text-indigo-600 hover:text-indigo-800 mr-4 transition duration-200">Edit</a>
                            <form action="{{ route('admin.movie.delete', $movie) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 transition duration-200"
                                    onclick="return confirm('Are you sure you want to delete this movie?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No movies found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $movies->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: Add any additional JavaScript if needed for interactivity
</script>
@endsection