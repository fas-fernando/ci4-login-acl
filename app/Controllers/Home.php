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
}
