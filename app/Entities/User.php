<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Libraries\Token;

class User extends Entity
{
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    public function showSituation()
    {
        if($this->deleted_at != null) {
            $icon = '<span class="text-white">Excluído</span>&nbsp;-&nbsp;<i class="fa fa-undo"></i>&nbsp;Restaurar';

            $situation = anchor("users/restore/$this->id", $icon, ['class' => 'btn btn-outline-success btn-sm']);

            return $situation;
        }

        if($this->status == true) return '<i class="text-success fa fa-unlock"></i> <span class="text-success">Ativo</span>';

        if($this->status == false) return '<i class="text-danger fa fa-lock"></i> <span class="text-danger">Inativo</span>';
    }

    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }

    public function havePermissionTo(string $permission): bool
    {
        if($this->is_admin === true) return true;

        if(empty($this->permissions)) return false;

        if(in_array($permission, $this->permissions) == false) return false;

        return true;
    }

    public function startPasswordReset(): void
    {
        $token = new Token();

        $this->reset_token = $token->getValue();

        $this->reset_hash = $token->getHash();

        $this->reset_expires_in = date('Y-m-d H:i:s', time() + 7200);
    }
}
