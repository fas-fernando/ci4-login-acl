<?php

namespace App\Libraries;

class Authentication
{
    private $user;
    private $userModel;

    public function __construct()
    {
        $this->userModel = model("App\Models\UserModel");
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->userModel->getUserByEmail($email);

        if($user === null) return false;
        
        if($user->checkPassword($password) == false) return false;

        if($user->status == false) return false;

        $this->userLogin($user);

        return true;
    }

    public function logout(): void
    {
        session()->destroy();
    }

    public function getUserLogged()
    {
        if($this->user === null) $this->user = $this->getUserforSession();

        return $this->user;
    }

    public function isLogged(): bool
    {
        return $this->getUserLogged() !== null;
    }

    private function userLogin(object $user): void
    {
        $session = session();
        $_SESSION['__ci_last_regenerate'] = time();
        $session->set('user_id', $user->id);
    }

    private function getUserforSession()
    {
        if(session()->has('user_id') == false) return null;

        $user = $this->userModel->find(session()->get('user_id'));

        if($user == null || $user->status == null) return null;

        return $user;
    }
}