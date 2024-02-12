<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    public function new()
    {
        $data = [
            'title' => 'Realize o login',
        ];

        return view('Login/new', $data);
    }

    public function store()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $auth = service('auth');

        if($auth->login($email, $password) === false) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['credentials' => 'Credenciais invalidas'];

            return $this->response->setJSON($returnData);
        }

        $userLogged = $auth->getUserLogged();

        session()->setFlashdata('success', "Olá $userLogged->username, que bom ver você aqui!");

        if($userLogged->is_client) {
            $returnData['redirect'] = 'orders/my';
            return $this->response->setJSON($returnData);
        }

        $returnData['redirect'] = 'home';
        return $this->response->setJSON($returnData);
    }
}
