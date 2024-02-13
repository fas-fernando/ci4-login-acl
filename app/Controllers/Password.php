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

    public function reset()
    {
        if(!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $email = $this->request->getPost('email');

        $user = $this->userModel->getUserByEmail(strval($email));

        if($user === null || $user->status === false) {
            $returnData['error'] = 'Não encontramos uma conta válida';
            return $this->response->setJSON($returnData);
        }

        $user->startPasswordReset();

        $this->userModel->save($user);

        return $this->response->setJSON([]);
    }

    public function sendReset()
    {
        $data = [
            'title' => 'E-mail de recuperação enviado',
        ];

        return view('Password/send_reset', $data);
    }
}
