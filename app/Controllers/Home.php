<?php

namespace App\Controllers;

use App\Libraries\Authentication;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home'
        ];

        return view('Home/index', $data);
    }

    public function login()
    {
        $auth = new Authentication();

        // $auth->login('fernando@email.com', '123456');

        dd($auth->getUserLogged());

        // $auth->logout();
    }
}
