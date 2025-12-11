<?php

namespace App\Controllers;

use App\Models\FilmModel;
use App\Models\FavoriteModel;

class Home extends BaseController
{
    protected $filmModel;
    protected $session;

    public function __construct()
    {
        $this->filmModel = new FilmModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/auth/login');
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // Ambil semua film dari database lokal
        $allFilms = $this->filmModel->getAllFilms();

        // Ambil film trending (misalnya berdasarkan rating tertinggi)
        $trending_films = $this->filmModel
            ->orderBy('rating', 'DESC')
            ->limit(6)
            ->findAll();

        // ========================================
        // FAVORITE MOVIES FROM DB
        // ========================================
        $favModel = new FavoriteModel();
        $userId = $this->session->get('user_id');
        $userFavorites = [];
        if ($userId) {
            $userFavorites = $favModel->where('user_id', $userId)->findColumn('movie_id');
            if (!is_array($userFavorites)) $userFavorites = [];
        }

        $data = [
            'title'          => 'Beranda - Movix',
            'user'           => [
                'email' => $this->session->get('email'),
                'role'  => $this->session->get('role')
            ],
            'films'          => $allFilms,
            'trending_films' => $trending_films,
            'userFavorites'  => $userFavorites,
        ];

        return view('home/dashboard', $data);
    }

    // =========================================================================
    // LOCAL FILM WATCH
    // =========================================================================
    public function watchFilm($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $film = $this->filmModel->getFilmById($id);

        if (!$film) {
            return redirect()->to('/dashboard')->with('error', 'Film tidak ditemukan!');
        }

        $data = [
            'title' => $film['title'] . ' - Movix',
            'film'  => $film,
            'user'  => [
                'email' => $this->session->get('email'),
                'role'  => $this->session->get('role')
            ]
        ];

        return view('home/watch', $data);
    }

    // =========================================================================
    // SEARCH
    // =========================================================================
    public function search()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $keyword = $this->request->getGet('q');

        if (!$keyword) {
            return redirect()->to('/dashboard');
        }

        $films = $this->filmModel->searchFilms($keyword);

        $data = [
            'title'   => 'Pencarian: ' . $keyword,
            'user'    => [
                'email' => $this->session->get('email'),
                'role'  => $this->session->get('role')
            ],
            'films'   => $films,
            'keyword' => $keyword
        ];

        return view('home/search', $data);
    }

    // =========================================================================
    // WATCH - Support Archive.org URL
    // =========================================================================
    public function watch($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data film
        $film = $this->filmModel->getFilmById($id);

        if (!$film) {
            return redirect()->to('/dashboard')->with('error', 'Film tidak ditemukan!');
        }

        // ========== ARCHIVE.ORG PLAYER ==========
        // video_path bisa berupa:
        // 1. Archive.org identifier: "steamboat-willie_1928"
        // 2. Full URL: "https://archive.org/download/..."
        
        $embed = null;
        
        if (!empty($film['video_path'])) {
            // Jika sudah full URL, gunakan langsung
            if (strpos($film['video_path'], 'http') === 0) {
                $embed = $film['video_path'];
            } else {
                // Jika hanya identifier, buat embed URL
                $embed = "https://archive.org/embed/{$film['video_path']}";
            }
        }

        return view('home/watch', [
            'film'  => $film,
            'embed' => $embed,
            'title' => $film['title'] . " – Watch Movie"
        ]);
    }

    // =========================================================================
    // DETAIL - Film Lokal
    // =========================================================================
    public function detail($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // Ambil film dari database lokal
        $film = $this->filmModel->getFilmById($id);

        if (!$film) {
            return redirect()->to('/dashboard')->with('error', 'Film tidak ditemukan!');
        }

        // URL Poster lokal
        $poster = base_url('uploads/posters/' . ($film['poster_path'] ?? 'placeholder.jpg'));

        $data = [
            'title' => $film['title'] . ' - Detail Film',
            'user'  => [
                'email' => $this->session->get('email'),
            ],
            'film'  => $film,
            'poster'=> $poster
        ];

        return view('home/detail', $data);
    }
}