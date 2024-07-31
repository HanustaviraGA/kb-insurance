<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Admin 1',
                'email'    => 'john@kb.com',
                'password' => password_hash('password123', PASSWORD_BCRYPT),
            ],
            [
                'name' => 'Admin 2',
                'email'    => 'jane@kb.com',
                'password' => password_hash('password456', PASSWORD_BCRYPT),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
