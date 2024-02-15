<?php

namespace App\Controllers;

use PhpParser\Node\Stmt\TryCatch;

class Migrate extends \CodeIgniter\Controller
{
    public function index()
    {
        $migrate = \Config\Services::migrations();

        try {
            $migrate->latest();

            echo 'Tabelas criadas com sucesso';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}