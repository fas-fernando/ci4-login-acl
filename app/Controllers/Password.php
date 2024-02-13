<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Password extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }

    public function forgot()
    {
        $data = [
            'title' => 'Esqueci minha senha',
        ];

        return view('Password/forgot', $data);
    }
}
