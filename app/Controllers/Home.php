<?php

namespace App\Controllers;

use CodeIgniter\Database\RawSql;

class Home extends BaseController
{
    public function index(): string
    {   

        return view('welcome_message');
    }
}
