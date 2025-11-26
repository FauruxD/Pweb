<?php

namespace App\Controllers;

class Genre extends BaseController
{
    protected $apiKey;
    protected $baseUrl;
    protected $imageUrl;

    public function __construct()
    {
        $this->apiKey   = getenv('TMDB_API_KEY');
        $this->baseUrl  = getenv('TMDB_BASE_URL');
        $this->imageUrl = getenv('TMDB_IMAGE_URL');
    }

    /* ============================================================
       🔥 SUPER FAST CURL
    ============================================================ */
    private function curlGet($url)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res ?: null;
    }

    /* ============================================================
       🧊 CACHE FILE (Hemat API & Super Cepat)
    ============================================================ */
    private function cached($key, $callback, $duration = 3600)
    {
        $file = WRITEPATH . "cache/{$key}.json";

        if (file_exists($file)) {
            $json = json_decode(file_get_contents($file), true);

            if (!empty($json) && (time() - filemtime($file) < $duration)) {
                return $json;
            }
        }

        $data = $callback();

        if (!empty($data)) {
            file_put_contents($file, json_encode($data));
        }

        return $data;
    }

    /* ============================================================
       📌 GENRE INDEX
    ============================================================ */
    public function index()
    {
        // AMBIL SEMUA GENRE SEKALI SAJA (CACHED)
        $tmdbGenres = $this->cached("tmdb_genres", function () {
            $url = "{$this->baseUrl}/genre/movie/list?api_key={$this->apiKey}&language=id-ID";
            $json = json_decode($this->curlGet($url), true);
            return $json['genres'] ?? [];
        }, 86400);

        // MAPPING LOKAL ↔ TMDB
        $mapping = [
            'aksi'       => 28,
            'drama'      => 18,
            'komedi'     => 35,
            'horror'     => 27,
            'romance'    => 10749,
            'animasi'    => 16,
            'dokumenter' => 99,
            'scifi'      => 878,
        ];

        $genres = [];

        foreach ($mapping as $key => $genreId) {

            // Cari nama genre
            $name = '';
            foreach ($tmdbGenres as $g) {
                if ($g['id'] == $genreId) {
                    $name = $g['name'];
                    break;
                }
            }

            // TOTAL FILM PER GENRE — JUGA DI CACHE
            $totalMovies = $this->cached("genre_total_{$genreId}", function () use ($genreId) {
                $url = "{$this->baseUrl}/discover/movie?api_key={$this->apiKey}&with_genres={$genreId}";
                $json = json_decode($this->curlGet($url), true);
                return $json['total_results'] ?? 0;
            }, 7200);

            $genres[$key] = [
                'id'    => $genreId,
                'name'  => $name,
                'total' => $totalMovies
            ];
        }

        return view('genre/index', [
            'genres' => $genres
        ]);
    }

    /* ============================================================
       📌 SHOW FILM PER GENRE
    ============================================================ */
    public function show($id)
    {
        // CACHE AGAR TIDAK LEMOT
        $films = $this->cached("genre_movies_{$id}", function () use ($id) {
            $url = "{$this->baseUrl}/discover/movie?api_key={$this->apiKey}&with_genres={$id}&language=id-ID";
            $json = json_decode($this->curlGet($url), true);
            return $json['results'] ?? [];
        }, 3600);

        return view('genre/genre_movies', [
            'films'    => $films,
            'imageUrl' => $this->imageUrl
        ]);
    }
}
