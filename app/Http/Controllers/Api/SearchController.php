<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Movie;

class SearchController extends Controller
{
    public function searchFullText(Request $request)
    {
        $moviesPerPage = 20;
        if ($request->has('page')) {
            $page = $request->input('page');
            if (!is_numeric($page) || $page <= 0) {
                $page = 1;
            }
        }
        $query = Movie::query()->with('category')->withCount('episodes');
        $keyword = $request->input('keyword');
        if ($keyword && is_string($keyword)) {
            $keyword = trim($keyword);
            $keywordLength = mb_strlen($keyword, 'UTF-8');
        }


        if ($keyword && is_string($keyword) && $keywordLength > 2) {
            $keyword = trim($request->input('keyword'));

            $nameWeight = 4.0;
            $originNameWeight = 3.0;
            $genresWeight = 2.5;
            $query->whereRaw(
                "MATCH (name, description, genres, actor, director, origin_name) AGAINST (? IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)",
                [$keyword]
            );
            $query->select('*');

            $query->selectRaw(
                "MATCH (name) AGAINST (? IN NATURAL LANGUAGE MODE) as name_score",
                [$keyword]
            );
            $query->selectRaw(
                "MATCH (origin_name) AGAINST (? IN NATURAL LANGUAGE MODE) as origin_name_score",
                [$keyword]
            );

            $query->selectRaw(
                "MATCH (genres) AGAINST (? IN NATURAL LANGUAGE MODE) as genres_score",
                [$keyword]
            );
            $query->selectRaw(
                "MATCH (description, actor, director) AGAINST (? IN NATURAL LANGUAGE MODE) as other_score",
                [$keyword]
            );


            $query->orderByRaw(
                "(name_score * ?) + (origin_name_score * ?) + (genres_score * ?) + other_score DESC",
                [$nameWeight, $originNameWeight, $genresWeight]
            );
        } else {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }
        $movies = $query->with('category')->skip(($page - 1) * $moviesPerPage)->paginate($moviesPerPage);
        $movies->load(['packages' => function ($query) {
            $query->select('packages.id', 'packages.name', 'packages.price');
        }]);
        return response()->json($movies);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        if (!$keyword) {
            return response()->json([
                'status' => 'error',
                'message' => 'Keyword is required'
            ])->setStatusCode(400);
        }

        $movies = Movie::where('name', 'LIKE', '%' . $keyword . '%')
            ->select('id', 'name', 'slug')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'movies' => $movies
        ])->setStatusCode(200);
    }

    public function getCategory()
    {
        $categories = Category::select('id', 'name', 'slug')->get();
        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ])->setStatusCode(200);
    }
}
