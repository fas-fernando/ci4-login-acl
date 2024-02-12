<?php

namespace App\Libraries;

class Authentication
{
    private $user;
    private $userModel;
    private $groupUserModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->groupUserModel = new \App\Models\GroupUserModel();
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

        $user = $this->definedPermissionsUserLogged($user);

        return $user;
    }

    private function isAdmin(): bool
    {
        $groupAdmin = 1;
        $user_id = session()->get('user_id');

        $administrator = $this->groupUserModel->userIsInGroup($groupAdmin, $user_id);

        if($administrator == null) return false;

        return true;
    }

    private function isClient(): bool
    {
        $groupClient = 2;
        $user_id = session()->get('user_id');

        $client = $this->groupUserModel->userIsInGroup($groupClient, $user_id);

        if($client == null) return false;

        return true;
    }

    private function definedPermissionsUserLogged(object $user): object
    {
        $user->is_admin = $this->isAdmin();

        if($user->is_admin == true) {
            $user->is_client = false;
        } else {
            $user->is_client = $this->isClient();
        }

        if($user->is_admin == false && $user->is_client == false) {
            $user->permissions = $this->getPermissionsUserLogged();
        }

        return $user;
    }

    private function getPermissionsUserLogged(): array
    {
        $permissionsForUser = $this->userModel->getPermissionsUserLogged(session()->get('user_id'));

        return array_column($permissionsForUser, 'permission');
    }
}