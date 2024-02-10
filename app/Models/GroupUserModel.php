<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupUserModel extends Model
{
    protected $table            = 'groups_users';
    protected $returnType       = 'object';
    protected $allowedFields    = ['group_id', 'user_id'];
}
