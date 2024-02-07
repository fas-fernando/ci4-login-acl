<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\User;
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
        if(!$this->request->isAJAX()) return redirect()->back();

        $attr = [
            'id',
            'username',
            'email',
            'status',
            'avatar',
        ];

        $users = $this->userModel->select($attr)->orderBy('id', 'DESC')->findAll();

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

    public function create()
    {
        $user = new User();

        $data = [
            'title' => 'Criação do usuário',
            'user'  => $user,
        ];

        return view("Users/create", $data);
    }

    public function store()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = new User($post);

        if($this->userModel->protect(false)->insert($user)) {
            $btnNewUser = anchor("users/create", "Novo usuário", ['class' => 'btn btn-warning btn-sm mt-3']);

            session()->setFlashdata('success', "Dados salvos com sucesso <br> $btnNewUser");

            $returnData['id'] = $this->userModel->getInsertID();
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

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

    public function edit(int $id = null)
    {
        $user = $this->getUserOr404($id);

        $data = [
            'title' => 'Editar usuário ' . esc($user->username),
            'user'  => $user,
        ];

        return view("Users/edit", $data);
    }

    public function update()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = $this->getUserOr404($post['id']);

        if(empty($post['password'])) {
            unset($post['password']);
            unset($post['password_confirmation']);
        }

        $user->fill($post);

        if($user->hasChanged() == false) {
            $returnData['info'] = 'Não há dados para serem atualizados';
            return $this->response->setJSON($returnData);
        }

        if($this->userModel->protect(false)->save($user)) {
            session()->setFlashdata('success', 'Dados salvos com sucesso');
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

        return $this->response->setJSON($returnData);
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