<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Watch extends BaseController
{
    public function index($slug)
    {
        // Ambil data film dari database lokal atau dari archive.org
        // Untuk sementara contoh manual:
        $film = [
            'title'  => 'Night of the Living Dead (1968)',
            'year'   => '1968',
            'genre'  => 'Horror',
            'description' => 'Film klasik public domain.',
            'rating' => 8.5
        ];

        // Ambil user dari session
        $user = session()->get('user');

        $data = [
            'title' => $film['title'] . ' - MOVIX',
            'film' => $film,
            'user' => $user,
            'embed' => 'https://archive.org/embed/night_of_the_living_dead'
        ];

        return view('home/watch', $data);
    }
}
