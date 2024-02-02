<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = model('App\Models\UserModel');
    }

    public function index()
    {
        $data = [
            'title' => 'Lista de usuários',
        ];

        return view('Users/index', $data);
    }
}
