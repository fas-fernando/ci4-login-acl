<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Group extends Entity
{
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];

    public function showSituation()
    {
        if($this->deleted_at != null) {
            $icon = '<span class="text-white">Excluído</span>&nbsp;-&nbsp;<i class="fa fa-undo"></i>&nbsp;Restaurar';

            $situation = anchor("groups/restore/$this->id", $icon, ['class' => 'btn btn-outline-success btn-sm']);

            return $situation;
        }

        if($this->show == true) return '<i class="fa fa-eye text-secondary"></i>&nbsp;Exibir Grupo';

        if($this->status == false) return '<i class="fa fa-eye-slash text-danger"></i>&nbsp;Não Exibir Grupo';
    }
}
