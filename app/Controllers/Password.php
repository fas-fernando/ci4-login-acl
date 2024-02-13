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
        if (!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $email = $this->request->getPost('email');

        $user = $this->userModel->getUserByEmail(strval($email));

        if ($user === null || $user->status === false) {
            $returnData['error'] = 'Não encontramos uma conta válida';
            return $this->response->setJSON($returnData);
        }

        $user->startPasswordReset();

        $this->userModel->save($user);

        $this->sendEmailResetPassword($user);

        return $this->response->setJSON([]);
    }

    public function sendReset()
    {
        $data = [
            'title' => 'E-mail de recuperação enviado',
        ];

        return view('Password/send_reset', $data);
    }

    public function resetPassword(string $token = null)
    {
        if ($token === null) return redirect()->to(site_url("password/forgot"))->with('attention', 'Link inválido ou expirado');

        $user = $this->userModel->getUserForResetPassword($token);

        if ($user === null) return redirect()->to(site_url("password/forgot"))->with('attention', 'Link inválido ou expirado');

        $data = [
            'title' => 'Crie a sua nova senha',
            'token' => $token,
        ];

        return view('Password/reset', $data);
    }

    public function updatePassword()
    {
        if (!$this->request->isAJAX()) return redirect()->back();

        $returnData['token'] = csrf_hash();

        $post = $this->request->getPost();

        $user = $this->userModel->getUserForResetPassword($post['token']);

        if ($user === null) {
            $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
            $returnData['errors_model'] = ['link_invalido' => 'Link inválido ou expirado'];

            return $this->response->setJSON($returnData);
        }

        $user->fill($post);

        $user->finishPasswordReset();

        if($this->userModel->save($user)) {
            session()->setFlashdata('success', 'Sua senha foi atualizada com sucesso');
            
            return $this->response->setJSON($returnData);
        }

        $returnData['error'] = 'Por favor, verifique os erros abaixo e tente novamente';
        $returnData['errors_model'] = $this->userModel->errors();

        return $this->response->setJSON($returnData);
    }

    private function sendEmailResetPassword(object $user): void
    {
        $email = service('email');

        $email->setFrom('order_service@etmo.com.br', 'OS');
        $email->setTo($user->email);

        $email->setSubject('Redefinição de senha');

        $data = [
            'token' => $user->reset_token,
        ];

        $message = view("Password/reset_email", $data);

        $email->setMessage($message);

        $email->send();
    }
}
