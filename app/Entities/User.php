<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

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
}
