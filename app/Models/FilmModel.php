<?php

namespace App\Models;

use CodeIgniter\Model;

class FilmModel extends Model
{
    protected $table = 'films';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'genre', 'year', 'video_path', 'poster_path', 'rating', 'description', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    
    protected $validationRules = [
        'title' => 'required|min_length[2]',
        'genre' => 'required',
        'year' => 'required|numeric',
        'video_path' => 'required'
    ];
    
    protected $validationMessages = [
        'title' => [
            'required' => 'Judul film harus diisi',
            'min_length' => 'Judul film minimal 2 karakter'
        ],
        'genre' => [
            'required' => 'Genre harus diisi'
        ],
        'year' => [
            'required' => 'Tahun rilis harus diisi',
            'numeric' => 'Tahun rilis harus berupa angka'
        ]
    ];
}