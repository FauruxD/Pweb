<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        // Redirect to login page
        return redirect()->to('/auth/login');
    }

    public function register()
    {
        if ($this->session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    public function processRegister()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'User',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/auth/login')->with('success', 'Pendaftaran berhasil! Silakan login.');
        }

        return redirect()->back()->withInput()->with('error', 'Pendaftaran gagal. Silakan coba lagi.');
    }

    public function login()
    {
        if ($this->session->get('logged_in')) {
            $role = $this->session->get('role');
            if ($role === 'Admin') {
                return redirect()->to('/admin/kelolafilm');
            }
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        $user = $this->userModel->verifyPassword($email, $password);

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah!');
        }

        $sessionData = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true
        ];

        $this->session->set($sessionData);

        if ($user['role'] === 'Admin') {
            return redirect()->to('/admin/kelolafilm');
        }

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/')->with('success', 'Anda berhasil logout.');
    }
}