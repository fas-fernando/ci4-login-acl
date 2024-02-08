<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupTempSeeder extends Seeder
{
    public function run()
    {
        $groupModel = model('App\Models\GroupModel');

        $groups = [
            [
                'name'        => 'Administrador',
                'description' => 'Grupo com acesso total ao sistema',
                'show'        => false,
            ],
            [
                'name'        => 'Clientes',
                'description' => 'Esse grupo é destinado para atribuição de clientes. Pois os mesmos poderão acessar o sistema e visualizar suas ordens de serviço.',
                'show'        => false,
            ],
            [
                'name'        => 'Atendentes',
                'description' => 'Esse grupo acessa o sistema para realizar atendimentos aos clientes.',
                'show'        => false,
            ],
        ];

        foreach($groups as $group) {
            $groupModel->insert($group);
        }

        echo "Grupos criados com sucesso";
    }
}
