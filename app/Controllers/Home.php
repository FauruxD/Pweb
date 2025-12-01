<?php

namespace App\Controllers;

use App\Models\FilmModel;

class Home extends BaseController
{
    protected $filmModel;
    protected $session;

    protected $apiKey;
    protected $baseUrl;
    protected $imageUrl;

    public function __construct()
    {
        $this->filmModel = new FilmModel();
        $this->session = \Config\Services::session();

        // Ambil env
        $this->apiKey   = getenv('TMDB_API_KEY');
        $this->baseUrl  = getenv('TMDB_BASE_URL');
        $this->imageUrl = rtrim(getenv('TMDB_IMAGE_URL'), '/');
    }

    public function index()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return redirect()->to('/auth/login');
    }

    /* =========================================================================
       FIX TERPENTING: pastikan poster TMDB selalu memiliki leading slash "/"
       ========================================================================= */
    private function normalizePoster($poster)
    {
        if (!$poster) return null;
        if (strpos($poster, 'http') === 0) return $poster; // jika sudah full URL
        if ($poster[0] !== '/') return '/' . $poster;
        return $poster;
    }

    // =========================================================================
    // DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // LOCAL MOVIES
        $localFilms = $this->filmModel->getAllFilms();

        // TMDB POPULAR MOVIES
        $tmdbFilms = $this->cachedRequest("popular_movies", function () {
            return $this->getPopularFromTMDB();
        });

        // Tambahkan Joker (contoh by ID)
        $jokerMovie = $this->cachedRequest("movie_475557", function () {
            return $this->getMovieByIdFromTMDB(475557);
        });

        if (!empty($jokerMovie)) {
            $tmdbFilms[] = $jokerMovie;
        }

        // Mapping TMDB → Standard format film untuk dashboard
        $mappedTMDB = array_map(function ($m) {

            $poster = $this->normalizePoster($m['poster_path'] ?? null);

            return [
                'id'          => $m['id'] ?? null,
                'title'       => $m['title'] ?? 'No Title',
                'poster_path' => $poster,
                'genre'       => 'TMDB',
                'year'        => substr($m['release_date'] ?? '0000', 0, 4),
                'rating'      => $m['vote_average'] ?? 0,
                'is_tmdb'     => true
            ];
        }, $tmdbFilms);

        // Gabungkan local + TMDB
        $allFilms = array_merge($localFilms, $mappedTMDB);

        // TRENDING MOVIES
        $trending_films = $this->cachedRequest("trending_movies", function () {
            return $this->getTrendingFromTMDB();
        });

        // Normalisasi poster trending
        foreach ($trending_films as &$t) {
            $t['poster_path'] = $this->normalizePoster($t['poster_path'] ?? null);
        }

        $data = [
            'title'          => 'Beranda - Movix',
            'user'           => [
                'email' => $this->session->get('email'),
                'role'  => $this->session->get('role')
            ],
            'films'          => $allFilms,
            'trending_films' => $trending_films,
            'imageUrl'       => $this->imageUrl,
        ];

        return view('home/dashboard', $data);
    }

    // =========================================================================
    // CACHE FIX — mencegah cache kosong membuat TMDB hilang
    // =========================================================================
    private function cachedRequest($key, $callback, $duration = 300)
    {
        $cacheFile = WRITEPATH . "cache/{$key}.json";

        if (file_exists($cacheFile)) {

            $json = json_decode(file_get_contents($cacheFile), true);

            // File valid → gunakan cache
            if (!empty($json) && (time() - filemtime($cacheFile) < $duration)) {
                return $json;
            }
        }

        // Fetch ulang jika cache rusak/kosong
        $data = $callback();

        // Tulis cache hanya jika data valid
        if (!empty($data)) {
            file_put_contents($cacheFile, json_encode($data));
        }

        return $data;
    }

    // =========================================================================
    // cURL REQUEST
    // =========================================================================
    private function curlGet($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 2,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response ?: null;
    }

    // =========================================================================
    // TMDB API
    // =========================================================================
    private function getPopularFromTMDB()
    {
        $url = "{$this->baseUrl}/movie/popular?api_key={$this->apiKey}&language=id-ID&page=1";
        $result = json_decode($this->curlGet($url), true);
        return $result['results'] ?? [];
    }

    private function getTrendingFromTMDB()
    {
        $url = "{$this->baseUrl}/trending/movie/week?api_key={$this->apiKey}";
        $result = json_decode($this->curlGet($url), true);

        return array_slice($result['results'] ?? [], 0, 6);
    }

    private function getMovieByIdFromTMDB($id)
    {
        $url = "{$this->baseUrl}/movie/{$id}?api_key={$this->apiKey}&language=id-ID";
        return json_decode($this->curlGet($url), true);
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
    // DETAIL - Support TMDB & Local Films
    // =========================================================================
    public function detail($id)
    {
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // ===============================
        // 1. Coba Ambil dari Database Lokal
        // ===============================
        $film = $this->filmModel->getFilmById($id);

        // ===============================
        // 2. Jika TIDAK ADA → ambil dari TMDB
        // ===============================
        if (!$film) {

            // ambil film dari TMDB berdasarkan ID
            $tmdb = $this->getMovieByIdFromTMDB($id);

            if (!$tmdb) {
                return redirect()->to('/dashboard')->with('error', 'Film tidak ditemukan!');
            }

            // konversi data TMDB menjadi bentuk yg sama dengan data lokal
            $film = [
                'id'          => $tmdb['id'],
                'title'       => $tmdb['title'],
                'poster_path' => $tmdb['poster_path'],
                'genre'       => isset($tmdb['genres'][0]['name']) ? $tmdb['genres'][0]['name'] : 'Unknown',
                'year'        => substr($tmdb['release_date'] ?? '0000', 0, 4),
                'rating'      => $tmdb['vote_average'] ?? 0,
                'description' => $tmdb['overview'] ?? '',
                'is_tmdb'     => true
            ];
        } else {
            $film['is_tmdb'] = false; // data lokal
        }

        // ===============================
        // 3. ✅ FIX: Bikin URL Poster yang BENAR
        // ===============================
        $poster = $film['is_tmdb']
            ? $this->imageUrl . $film['poster_path']
            : base_url('uploads/posters/' . ($film['poster_path'] ?? 'placeholder.jpg')); // ✅ FIXED!

        // ===============================
        // 4. Kirim ke View
        // ===============================
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