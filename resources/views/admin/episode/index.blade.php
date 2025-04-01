@extends('layouts.admin')

@section('title', 'Episodes')
@section('page-title', 'Manage Episodes')

@section('content')
<div class="container mx-auto px-4 ">
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition">Bảng điều khiển</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('admin.movie.index') }}" class="text-gray-500 hover:text-blue-600 transition">Movies</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-blue-600 font-medium">Danh sách tập</li>
        </ol>
    </nav>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-semibold text-gray-800">Episodes List: {{ $movie->name }}</h3>
        <a href="{{ route('admin.movie.episodes.create', $movie) }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md transition duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add New Episode
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

    <!-- Episodes Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Episode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Release Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($episodes as $episode)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $episode->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $episode->title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $episode->episode_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $episode->release_date ? \Carbon\Carbon::parse($episode->release_date)->format('d/m/Y') : '' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.movie.episodes.edit', ['movie' => $movie, 'episode' => $episode]) }}"
                                class="text-indigo-600 hover:text-indigo-800 mr-4 transition duration-200">Edit</a>
                            <form action="{{ route('admin.movie.episodes.delete', ['movie' => $movie, 'episode' => $episode]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 transition duration-200"
                                    onclick="return confirm('Are you sure you want to delete this episode?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No episodes found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $episodes->links('pagination::tailwind') }}
    </div>
</div>
@endsection