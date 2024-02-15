<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    public function run()
    {
        $groupModel = new \App\Models\GroupModel();

        $groups = [
            [
                'name'        => 'Administrador',
                'show'        => 0,
                'description' => 'Grupo com acesso total ao sistema',
            ],
            [
                'name'        => 'Clientes',
                'show'        => 0,
                'description' => 'Grupo com acesso ao sistema apenas para visualizar suas ordens de serviço',
            ],
        ];

        foreach($groups as $group) {
            $groupModel->skipValidation(true)->protect(false)->insert($group);
        }

        $userModel = new \App\Models\UserModel();

        $user = [
            'username' => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => '123456',
            'status'   => true,
        ];

        $userModel->skipValidation(true)->protect(false)->insert($user);

        $groupUserModel = new \App\Models\GroupUserModel();

        $groupUser = [
            'group_id' => 1,
            'user_id'  => 1,
        ];

        $groupUserModel->protect(false)->insert($groupUser);

        echo 'Usuário Admin semeado com sucesso';
    }
}
