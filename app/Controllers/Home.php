<?php

namespace App\Controllers;

use App\Models\FilmModel;

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

    public function dashboard()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $films = $this->filmModel->getAllFilms();
        
        // Get trending films (first 3 films for demo)
        $trendingFilms = array_slice($films, 0, 3);
        
        $data = [
            'title' => 'Beranda - Movix',
            'user' => [
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'films' => $films,
            'trending_films' => $trendingFilms
        ];

        return view('home/dashboard', $data);
    }

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
            'film' => $film,
            'user' => [
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ]
        ];

        return view('home/watch', $data);
    }

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
            'title' => 'Pencarian: ' . $keyword,
            'user' => [
                'email' => $this->session->get('email'),
                'role' => $this->session->get('role')
            ],
            'films' => $films,
            'keyword' => $keyword
        ];

        return view('home/search', $data);
    }
}