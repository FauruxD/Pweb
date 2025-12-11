<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FavoriteModel;
use App\Models\FilmModel;

class Favorite extends BaseController
{
    public function index()
    {
        $favModel = new FavoriteModel();
        $filmModel = new FilmModel();
        $userId = session('user_id');

        // Ambil daftar film favorit user
        $movies = $favModel
            ->where('user_id', $userId)
            ->findAll();

        // Ambil rekomendasi dari database lokal
        $recommendations = $this->getLocalRecommendations();

        return view('favorite/index', [
            'title' => 'Favorit – Movix',
            'movies' => $movies,
            'recommendations' => $recommendations
        ]);
    }

    public function toggle()
    {
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        $data = $this->request->getJSON(true);

        $movieId = $data['movie_id'] ?? null;
        $title   = $data['title'] ?? '';
        $poster_path  = $data['poster'] ?? ($data['poster_path'] ?? ''); // Support both

        if (!$movieId) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid movie ID']);
        }

        $exists = $favModel->where([
            'user_id'  => $userId,
            'movie_id' => $movieId
        ])->first();

        if ($exists) {
            $favModel->delete($exists['id']);
            return $this->response->setJSON(['status' => 'removed']);
        }

        $favModel->insert([
            'user_id'     => $userId,
            'movie_id'    => $movieId,
            'title'       => $title,
            'poster_path' => $poster_path
        ]);

        return $this->response->setJSON(['status' => 'added']);
    }

    // =========================================================================
    // Ambil Rekomendasi Random dari Database Lokal
    // =========================================================================
    private function getLocalRecommendations($count = 5)
    {
        $filmModel = new FilmModel();

        try {
            // Ambil film random dari database
            return $filmModel
                ->orderBy('RAND()')
                ->limit($count)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recommendations: ' . $e->getMessage());
            return [];
        }
    }

    // =========================================================================
    // ALTERNATIVE: Get Recommendations by Genre
    // =========================================================================
    private function getRecommendationsByGenre($genre = 'Action', $count = 5)
    {
        $filmModel = new FilmModel();
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        try {
            return $filmModel
                ->where('genre', $genre)
                ->whereNotIn('id', function($builder) use ($favModel, $userId) {
                    return $favModel
                        ->select('movie_id')
                        ->where('user_id', $userId);
                })
                ->orderBy('rating', 'DESC')
                ->limit($count)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch genre recommendations: ' . $e->getMessage());
        }

        return [];
    }

    // =========================================================================
    // ALTERNATIVE: Get Top Rated Movies (Exclude Favorites)
    // =========================================================================
    private function getTopRatedMovies($count = 5)
    {
        $filmModel = new FilmModel();
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        try {
            return $filmModel
                ->whereNotIn('id', function($builder) use ($favModel, $userId) {
                    return $favModel
                        ->select('movie_id')
                        ->where('user_id', $userId);
                })
                ->orderBy('rating', 'DESC')
                ->limit($count)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch top rated: ' . $e->getMessage());
        }

        return [];
    }

    // =========================================================================
    // ALTERNATIVE: Get Recent Movies
    // =========================================================================
    private function getRecentMovies($count = 5)
    {
        $filmModel = new FilmModel();
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        try {
            return $filmModel
                ->whereNotIn('id', function($builder) use ($favModel, $userId) {
                    return $favModel
                        ->select('movie_id')
                        ->where('user_id', $userId);
                })
                ->orderBy('year', 'DESC')
                ->limit($count)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch recent movies: ' . $e->getMessage());
        }

        return [];
    }
}