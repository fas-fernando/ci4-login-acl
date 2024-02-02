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

    public function getUsers()
    {
        if(!$this->request->isAJAX())
            return redirect()->back();

        $attr = [
            'id',
            'username',
            'email',
            'status',
            'avatar',
        ];

        $users = $this->userModel->select($attr)->findAll();

        $data = [];

        foreach($users as $user) {
            $data[] = [
                'avatar'   => $user->avatar,
                'username' => esc($user->username),
                'email'    => esc($user->email),
                'status'   => ($user->status == true ? '<span class="text-success">Ativo</span>' : '<span class="text-danger">Inativo</span>' ),
            ];
        }

        $returnData = [
            'data' => $data,
        ];

        return $this->response->setJSON($returnData);
    }
}
