<?php

namespace App\Controllers;

use PhpParser\Node\Stmt\TryCatch;

class Seed extends \CodeIgniter\Controller
{
    public function index()
    {
        $seeder = \Config\Database::seeder();

        try {
            $seeder->call('MainSeeder');

            echo 'Dados iniciais criados com sucesso';
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}