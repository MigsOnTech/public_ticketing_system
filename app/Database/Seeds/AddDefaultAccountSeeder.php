<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddDefaultAccountSeeder extends Seeder
{
    public function run()
    {
        $data = array(
            "fullname" => "Admin User",
            "username" => "admin",
            "email" => "admin@gmail.com",
            "password" => '$2y$10$ZXrr6KqhYQBgU/JZT5xruuKCs5x1n1FnIgPlXpArwCdmjAZwk.omK',
            "role" => "ADMIN",
        );
         $this->db->table('user_account')->insert($data); 
    }
}
