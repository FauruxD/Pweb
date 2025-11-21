<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\FilmModel;

class Admin extends BaseController
{
    protected $userModel;
    protected $filmModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->filmModel = new FilmModel();
        $this->session = \Config\Services::session();
    }

    private function checkAuth()
    {
        if (!$this->session->get('logged_in') || $this->session->get('role') !== 'Admin') {
            return redirect()->to('/auth/login')->with('error', 'Akses ditolak!');
        }
        return null;
    }

    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Admin Dashboard',
            'total_films' => $this->filmModel->getFilmCount(),
            'total_users' => $this->userModel->getUserCount()
        ];

        return view('admin/dashboard', $data);
    }

    public function kelolafilm()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Kelola Film',
            'films' => $this->filmModel->getAllFilms(),
            'total_films' => $this->filmModel->getFilmCount(),
            'total_users' => $this->userModel->getUserCount()
        ];

        return view('admin/kelolafilm', $data);
    }

    public function kelolauser()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $data = [
            'title' => 'Kelola User',
            'users' => $this->userModel->findAll(),
            'total_films' => $this->filmModel->getFilmCount(),
            'total_users' => $this->userModel->getUserCount()
        ];

        return view('admin/kelolauser', $data);
    }

    public function tambahFilm()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $validation = \Config\Services::validation();

        $validation->setRules([
            'title' => 'required|min_length[1]',
            'genre' => 'required',
            'year' => 'required|integer',
            'video' => 'uploaded[video]|max_size[video,102400]|ext_in[video,mp4,avi,mkv,mov]',
            'poster' => 'uploaded[poster]|max_size[poster,5120]|ext_in[poster,jpg,jpeg,png,webp]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Upload Video - Keep original name
        $videoFile = $this->request->getFile('video');
        $videoName = $videoFile->getName();
        $videoFile->move(ROOTPATH . 'public/uploads/videos', $videoName);

        // Upload Poster - Keep original name
        $posterFile = $this->request->getFile('poster');
        $posterName = $posterFile->getName();
        $posterFile->move(ROOTPATH . 'public/uploads/posters', $posterName);

        $data = [
            'title' => $this->request->getPost('title'),
            'genre' => $this->request->getPost('genre'),
            'year' => $this->request->getPost('year'),
            'video_path' => $videoName,
            'poster_path' => $posterName,
            'description' => $this->request->getPost('description') ?? '',
            'rating' => $this->request->getPost('rating') ?? 0.0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->filmModel->insert($data)) {
            return redirect()->to('/admin/kelolafilm')->with('success', 'Film berhasil ditambahkan!');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan film!');
    }

    public function deleteFilm($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $film = $this->filmModel->find($id);
        
        if (!$film) {
            return redirect()->to('/admin/kelolafilm')->with('error', 'Film tidak ditemukan!');
        }

        // Delete video file
        if (file_exists(ROOTPATH . 'public/uploads/videos/' . $film['video_path'])) {
            unlink(ROOTPATH . 'public/uploads/videos/' . $film['video_path']);
        }

        // Delete poster file
        if (!empty($film['poster_path']) && file_exists(ROOTPATH . 'public/uploads/posters/' . $film['poster_path'])) {
            unlink(ROOTPATH . 'public/uploads/posters/' . $film['poster_path']);
        }

        if ($this->filmModel->delete($id)) {
            return redirect()->to('/admin/kelolafilm')->with('success', 'Film berhasil dihapus!');
        }

        return redirect()->to('/admin/kelolafilm')->with('error', 'Gagal menghapus film!');
    }

    public function deleteUser($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        if ($id == $this->session->get('user_id')) {
            return redirect()->to('/admin/kelolauser')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/kelolauser')->with('success', 'User berhasil dihapus!');
        }

        return redirect()->to('/admin/kelolauser')->with('error', 'Gagal menghapus user!');
    }
}