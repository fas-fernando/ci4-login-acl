<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserFakerSeeder extends Seeder
{
    public function run()
    {
        $userModel = model('UserModel');

        $faker = \Faker\Factory::create();

        $quantity = 20000;

        $userPush = [];

        for ($i = 0; $i < $quantity; $i++) {
            array_push($userPush, [
                'username' => $faker->unique()->name,
                'email' => $faker->unique()->email,
                'password_hash' => '123456',
                'status' => 1,
            ]);
        }

        $userModel
            ->skipValidation(true)
            ->protect(false)
            ->insertBatch($userPush);

        echo "$quantity usuários criados com sucesso";
    }
}
