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

    private function userLogin(object $user): void
    {
        $session = session();
        $session->regenerate();
        $session->set('user_id', $user->id);
    }
}