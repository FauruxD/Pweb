<?php

namespace App\Models;

use CodeIgniter\Model;

class FilmModel extends Model
{
    protected $table            = 'films';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'genre', 'year', 'video_path', 'poster_path', 'rating', 'description', 'created_at'];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'title' => 'required|min_length[1]|max_length[255]',
        'genre' => 'required|max_length[100]',
        'year'  => 'required|integer'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    public function getFilmCount()
    {
        return $this->countAllResults();
    }

    public function getAllFilms()
    {
        return $this->orderBy('created_at', 'DESC')->findAll();
    }

    public function getFilmById($id)
    {
        return $this->find($id);
    }

    public function searchFilms($keyword)
    {
        return $this->like('title', $keyword)
                    ->orLike('genre', $keyword)
                    ->findAll();
    }
}