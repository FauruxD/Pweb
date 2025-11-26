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
            'desc'   => 'Film klasik public domain.',
            'iframe' => 'https://archive.org/embed/night_of_the_living_dead', 
            'poster' => '/assets/images/posters/notld.jpg'
        ];
        

        return view('watch/watch', ['film' => $film]);
    }
}
