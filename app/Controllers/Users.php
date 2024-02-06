<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

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
            $username = esc($user->username);

            $data[] = [
                'avatar'   => $user->avatar,
                'username' => anchor("users/show/$user->id", $username, 'title="Exibir usuário ' . $username . '"'),
                'email'    => esc($user->email),
                'status'   => ($user->status == true ? '<i class="text-success fa fa-unlock"></i> <span class="text-success">Ativo</span>' : '<i class="text-danger fa fa-lock"></i> <span class="text-danger">Inativo</span>' ),
            ];
        }

        $returnData = [
            'data' => $data,
        ];

        return $this->response->setJSON($returnData);
    }

    public function show(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $data = [
            'title' => 'Info do usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/show", $data);
    }

    private function getUserOr404(int $id = null)
    {
        $user = $this->userModel->withDeleted(true)->find($id);

        if(!$id || !$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Não encontramos o usuário $id");
        }

        return $user;
    }
}