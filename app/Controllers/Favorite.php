<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FavoriteModel;

class Favorite extends BaseController
{
    public function index()
    {
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        $movies = $favModel
            ->where('user_id', $userId)
            ->findAll();

        return view('favorite/index', [
            'title' => 'Favorit – Movix',
            'movies' => $movies,
            'recommendations' => []
        ]);
    }

    public function toggle()
    {
        $favModel = new FavoriteModel();
        $userId = session('user_id');

        $data = $this->request->getJSON(true);

        $movieId = $data['movie_id'];
        $title   = $data['title'];
        $poster  = $data['poster'];

        $exists = $favModel->where([
            'user_id'  => $userId,
            'movie_id' => $movieId
        ])->first();

        if ($exists) {
            $favModel->delete($exists['id']);
            return $this->response->setJSON(['status' => 'removed']);
        }

        $favModel->insert([
            'user_id'  => $userId,
            'movie_id' => $movieId,
            'title'    => $title,
            'poster'   => $poster
        ]);

        return $this->response->setJSON(['status' => 'added']);
    }
}
