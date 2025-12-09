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

        // UPDATED: video_path sekarang text biasa, bukan URL, deskripsi tanpa minimal karakter
        $validation->setRules([
            'title' => 'required|min_length[1]',
            'description' => 'required', // UBAH: Hapus min_length
            'genre' => 'required',
            'year' => 'required|integer',
            'video_path' => 'required|min_length[3]|alpha_dash', // UBAH: Text biasa, hanya huruf, angka, dash, underscore
            'poster' => 'uploaded[poster]|max_size[poster,5120]|ext_in[poster,jpg,jpeg,png,webp]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal: ' . implode(', ', $validation->getErrors()));
        }

        // Upload Poster
        $posterFile = $this->request->getFile('poster');
        $posterName = 'placeholder.jpg';

        log_message('info', 'Poster file received: ' . ($posterFile ? 'YES' : 'NO'));
        
        if ($posterFile) {
            log_message('info', 'Original name: ' . $posterFile->getName());
            log_message('info', 'Is valid: ' . ($posterFile->isValid() ? 'YES' : 'NO'));
            log_message('info', 'Has moved: ' . ($posterFile->hasMoved() ? 'YES' : 'NO'));
            log_message('info', 'Error: ' . $posterFile->getErrorString());
        }

        if ($posterFile && $posterFile->isValid() && !$posterFile->hasMoved()) {
            $posterName = $posterFile->getRandomName();
            
            $posterPath = ROOTPATH . 'public/uploads/posters';
            if (!is_dir($posterPath)) {
                mkdir($posterPath, 0777, true);
                log_message('info', 'Created directory: ' . $posterPath);
            }
            
            try {
                if ($posterFile->move($posterPath, $posterName)) {
                    log_message('info', '✅ Poster saved successfully: ' . $posterName);
                } else {
                    log_message('error', '❌ Failed to move poster file');
                    $posterName = 'placeholder.jpg';
                }
            } catch (\Exception $e) {
                log_message('error', '❌ Exception moving poster: ' . $e->getMessage());
                $posterName = 'placeholder.jpg';
            }
        } else {
            log_message('error', '❌ Poster file validation failed or already moved');
        }

        // UPDATED: Ambil video_path sebagai text identifier saja
        $videoIdentifier = $this->request->getPost('video_path');
        
        // Bersihkan input (hapus spasi, lowercase, dll)
        $videoIdentifier = strtolower(trim($videoIdentifier));
        $videoIdentifier = preg_replace('/[^a-z0-9\-_]/', '', $videoIdentifier);

        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'genre' => $this->request->getPost('genre'),
            'year' => $this->request->getPost('year'),
            'video_path' => $videoIdentifier, // UBAH: Simpan hanya identifier
            'poster_path' => $posterName,
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

        // Delete poster file only
        if (!empty($film['poster_path']) && 
            $film['poster_path'] !== 'placeholder.jpg' && 
            file_exists(ROOTPATH . 'public/uploads/posters/' . $film['poster_path'])) {
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