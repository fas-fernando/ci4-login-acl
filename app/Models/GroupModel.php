<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table            = 'groups';
    protected $returnType       = 'App\Entities\Group';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'name',
        'description',
        'show',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'id'                => 'permit_empty|is_natural_no_zero',
        'name'              => 'required|min_length[3]|max_length[100]|is_unique[groups.name,id,{id}]',
        'description'       => 'required|max_length[240]',
    ];
    protected $validationMessages   = [
        'name'              => [
            'required'   => 'O campo nome é obrigatório',
            'min_length' => 'O campo nome deve conter no minimo 3 caracteres',
            'max_length' => 'O campo nome deve conter no máximo 100 caracteres',
            'is_unique'   => 'Esse grupo já está cadastrado',
        ],
        'description'                 => [
            'required'    => 'O campo descrição é obrigatório',
            'max_length'  => 'O campo descrição deve conter no máximo 240 caracteres',
        ],
    ];
}
