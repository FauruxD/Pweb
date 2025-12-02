<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FavoriteModel;

class Favorite extends BaseController
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // TMDB API Config
        $this->apiKey = getenv('TMDB_API_KEY');
        $this->baseUrl = getenv('TMDB_BASE_URL') ?: 'https://api.themoviedb.org/3';
    }

    public function index()
    {
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        $movies = $favModel
            ->where('user_id', $userId)
            ->findAll();

        // ✅ FITUR BARU: Ambil rekomendasi dari TMDB
        $recommendations = $this->getRandomRecommendations();

        return view('favorite/index', [
            'title' => 'Favorit – Movix',
            'movies' => $movies,
            'recommendations' => $recommendations // ✅ Kirim ke view
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
    // ✅ FUNGSI BARU: Ambil Rekomendasi Random dari TMDB
    // =========================================================================
    private function getRandomRecommendations($count = 5)
    {
        // Random page antara 1-5 untuk variasi
        $randomPage = rand(1, 5);

        $url = "{$this->baseUrl}/movie/popular?api_key={$this->apiKey}&language=id-ID&page={$randomPage}";

        try {
            $response = @file_get_contents($url);
            
            if ($response === false) {
                log_message('error', 'Failed to fetch TMDB recommendations');
                return [];
            }

            $data = json_decode($response, true);

            if (isset($data['results']) && !empty($data['results'])) {
                // Ambil random films dari results
                $results = $data['results'];
                shuffle($results); // Acak urutan
                return array_slice($results, 0, $count); // Ambil 12 film pertama
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching recommendations: ' . $e->getMessage());
        }

        return []; // Return empty jika gagal
    }

    // =========================================================================
    // ALTERNATIVE: Get Recommendations by Genre
    // =========================================================================
    private function getRecommendationsByGenre($genreId = 28, $count = 5)
    {
        // Genre IDs: 28=Action, 35=Comedy, 18=Drama, 27=Horror, 10749=Romance
        $url = "{$this->baseUrl}/discover/movie?api_key={$this->apiKey}&language=id-ID&with_genres={$genreId}&sort_by=popularity.desc";

        try {
            $response = @file_get_contents($url);
            
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);

            if (isset($data['results'])) {
                return array_slice($data['results'], 0, $count);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch genre recommendations: ' . $e->getMessage());
        }

        return [];
    }

    // =========================================================================
    // ALTERNATIVE: Get Top Rated Movies
    // =========================================================================
    private function getTopRatedMovies($count = 12)
    {
        $url = "{$this->baseUrl}/movie/top_rated?api_key={$this->apiKey}&language=id-ID&page=1";

        try {
            $response = @file_get_contents($url);
            
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);

            if (isset($data['results'])) {
                return array_slice($data['results'], 0, $count);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch top rated: ' . $e->getMessage());
        }

        return [];
    }

    // =========================================================================
    // ALTERNATIVE: Get Trending Movies
    // =========================================================================
    private function getTrendingMovies($count = 5)
    {
        $url = "{$this->baseUrl}/trending/movie/week?api_key={$this->apiKey}&language=id-ID";

        try {
            $response = @file_get_contents($url);
            
            if ($response === false) {
                return [];
            }

            $data = json_decode($response, true);

            if (isset($data['results'])) {
                return array_slice($data['results'], 0, $count);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch trending: ' . $e->getMessage());
        }

        return [];
    }
}