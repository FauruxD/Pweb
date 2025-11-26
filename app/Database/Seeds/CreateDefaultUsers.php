<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateDefaultUsers extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email'    => 'admin@gmail.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role'     => 'Admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email'    => 'user@gmail.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'role'     => 'User',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
