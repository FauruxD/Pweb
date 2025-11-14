<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function __construct()
    {
        helper(['form', 'url']);
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        $data['title'] = 'Login User';
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validator;
            } else {
                $model = new UserModel();
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                $user = $model->where('email', $email)->first();
                
                if ($user) {
                    if (password_verify($password, $user['password'])) {
                        $ses_data = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                            'role' => $user['role'],
                            'logged_in' => TRUE
                        ];
                        session()->set($ses_data);
                        
                        if ($user['role'] === 'Admin') {
                            return redirect()->to('/admin/dashboard');
                        } else {
                            return redirect()->to('/home');
                        }
                    } else {
                        session()->setFlashdata('error', 'Password salah');
                    }
                } else {
                    session()->setFlashdata('error', 'Email tidak ditemukan');
                }
            }
        }
        
        return view('auth/login', $data);
    }

    public function register()
    {
        $data['title'] = 'Daftar User';
        $data['validation'] = null;
        
        if ($this->request->getMethod() === 'post') {
            
            // Get form data
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $confirmPassword = $this->request->getPost('confirm_password');
            
            // Manual validation
            $errors = [];
            
            if (empty($email)) {
                $errors[] = 'Email harus diisi';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid';
            }
            
            if (empty($password)) {
                $errors[] = 'Password harus diisi';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Password minimal 6 karakter';
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = 'Password tidak cocok';
            }
            
            if (empty($errors)) {
                $db = \Config\Database::connect();
                $builder = $db->table('users');
                $existingUser = $builder->where('email', $email)->get()->getRow();
                
                if ($existingUser) {
                    $errors[] = 'Email sudah terdaftar';
                }
            }
            
            if (!empty($errors)) {
                session()->setFlashdata('error', implode('<br>', $errors));
                return redirect()->back()->withInput();
            }
            
            try {
                $db = \Config\Database::connect();
                $builder = $db->table('users');
                
                $insertData = [
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => 'User'
                ];
                
                $inserted = $builder->insert($insertData);
                
                if ($inserted) {
                    session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
                    return redirect()->to('/auth/login');
                } else {
                    $error = $db->error();
                    session()->setFlashdata('error', 'Gagal menyimpan data: ' . $error['message']);
                    return redirect()->back()->withInput();
                }
                
            } catch (\Exception $e) {
                session()->setFlashdata('error', 'Error: ' . $e->getMessage());
                return redirect()->back()->withInput();
            }
        }
        
        return view('auth/register', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}