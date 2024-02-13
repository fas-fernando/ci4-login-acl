<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Libraries\Token;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $returnType       = 'App\Entities\User';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'reset_hash',
        'reset_expires_in',
        'avatar',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id'                    => 'permit_empty|is_natural_no_zero',
        'username'              => 'required|min_length[3]|max_length[100]',
        'email'                 => 'required|valid_email|is_unique[users.email,id,{id}]|max_length[240]',
        'password'              => 'required|min_length[6]',
        'password_confirmation' => 'required_with[password]|matches[password]',
    ];
    protected $validationMessages   = [
        'username'              => [
            'required'   => 'O campo nome é obrigatório',
            'min_length' => 'O campo nome deve conter no minimo 3 caracteres',
            'max_length' => 'O campo nome deve conter no máximo 100 caracteres',
        ],
        'email'                 => [
            'required'    => 'O campo email é obrigatório',
            'valid_email' => 'Informe um email válido',
            'is_unique'   => 'Esse email já está cadastrado',
            'max_length'  => 'O campo email deve conter no máximo 240 caracteres',
        ],
        'password'              => [
            'required'    => 'O campo senha é obrigatório',
            'min_length'  => 'O campo senha deve conter no minimo 6 caracteres',
        ],
        'password_confirmation' => [
            'required_with' => 'Por favor, confirme sua senha',
            'matches'       => 'As senhas precisam combinar',
        ],
    ];

    // Callbacks
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if(isset($data['data']['password'])) {
            $data['data']['password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

            unset($data['data']['password']);
            unset($data['data']['password_confirmation']);
        }

        return $data;
    }

    public function getUserByEmail(string $email)
    {
        return $this->where('email', $email)->where('deleted_at', null)->first();
    }

    public function getPermissionsUserLogged(int $user_id)
    {
        $attr = [
            'users.id',
            'users.username AS username',
            'groups_users.*',
            'permissions.name AS permission'
        ];

        return $this->select($attr)
            ->asArray()
            ->join('groups_users', 'groups_users.user_id = users.id')
            ->join('groups_permissions', 'groups_permissions.group_id = groups_users.group_id')
            ->join('permissions', 'permissions.id = groups_permissions.permission_id')
            ->where('user_id', $user_id)
            ->groupBy('permissions.name')
            ->findAll();
    }

    public function getUserForResetPassword(string $token)
    {
        $token = new Token($token);

        $tokenHash = $token->getHash();

        $user = $this->where('reset_hash', $tokenHash)->where('deleted_at', null)->first();

        if($user === null) return null;

        if($user->reset_expires_in < date('Y-m-d H:i:s')) return null;

        return $user;
    }
}
