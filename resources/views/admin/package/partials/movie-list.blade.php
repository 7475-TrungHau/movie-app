@foreach($movies as $movie)
<tr class="movie-item hover:bg-gray-50 transition-colors cursor-pointer" data-id="{{ $movie->id }}" data-type="{{ $movie->type ?? 'movie' }}">
    <td class="px-6 py-4 whitespace-nowrap">
        <input type="checkbox" name="movie_ids[]" id="movie-{{ $movie->id }}" value="{{ $movie->id }}"
            {{ (isset($selectedMovieIds) && in_array($movie->id, $selectedMovieIds)) ? 'checked' : '' }}
            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $movie->id }}</td>
    <td class="px-6 py-4 whitespace-nowrap">
        @if($movie->thumbnail_url)
        <img src="{{ $movie->thumbnail_url }}" alt="{{ $movie->name }}" class="h-12 w-16 object-cover rounded-md">
        @else
        <div class="h-12 w-16 bg-gray-100 rounded-md flex items-center justify-center">
            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        @endif
    </td>
    <td class="px-6 py-4">
        <label for="movie-{{ $movie->id }}" class="block text-sm font-medium text-gray-900 cursor-pointer">{{ $movie->name }}</label>
    </td>
    <td class="px-6 py-4 text-sm text-gray-500">{{ $movie->genres ?? 'Chưa có' }}</td>
    <td class="px-6 py-4 text-sm text-gray-500">{{ $movie->episodes_count ?? 0 }}</td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $movie->type == 'series' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
            {{ $movie->type == 'series' ? 'Series' : 'Phim lẻ' }}
        </span>
    </td>
</tr>
@endforeach