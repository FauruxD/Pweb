<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\FilmModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $filmModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->filmModel = new FilmModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Check if user is admin
        if (session()->get('role') !== 'Admin') {
            return redirect()->to('/home')->with('error', 'Akses ditolak');
        }

        $data = [
            'title' => 'Admin Dashboard',
            'total_films' => $this->filmModel->countAll(),
            'total_users' => $this->userModel->countAll(),
            'active_tab' => 'film'
        ];

        return view('admin/dashboard', $data);
    }

    public function getFilms()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/dashboard');
        }

        $films = $this->filmModel->findAll();
        return $this->response->setJSON($films);
    }

    public function getUsers()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/dashboard');
        }

        $users = $this->userModel->findAll();
        return $this->response->setJSON($users);
    }

    public function addFilm()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/dashboard');
        }

        $rules = [
            'title' => 'required|min_length[2]',
            'genre' => 'required',
            'year' => 'required|numeric',
            'video' => 'uploaded[video]|max_size[video,102400]|ext_in[video,mp4,mkv,avi]',
            'poster' => 'uploaded[poster]|max_size[poster,2048]|ext_in[poster,jpg,jpeg,png,webp]',
            'rating' => 'permit_empty|decimal',
            'description' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $video = $this->request->getFile('video');
        $videoName = $video->getRandomName();
        $video->move('uploads/videos', $videoName);

        $data = [
            'title' => $this->request->getPost('title'),
            'genre' => $this->request->getPost('genre'),
            'year' => $this->request->getPost('year'),
            'video_path' => $videoName
        ];

        if ($this->filmModel->save($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Film berhasil ditambahkan'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menambahkan film'
        ]);
    }

    public function deleteFilm($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/dashboard');
        }

        $film = $this->filmModel->find($id);
        if ($film) {
            // Delete video file
            $videoPath = 'uploads/videos/' . $film['video_path'];
            if (file_exists($videoPath)) {
                unlink($videoPath);
            }

            if ($this->filmModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Film berhasil dihapus'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus film'
        ]);
    }

    public function deleteUser($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->to('/admin/dashboard');
        }

        // Prevent deleting own account
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ]);
        }

        if ($this->userModel->delete($id)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Gagal menghapus user'
        ]);
    }
}