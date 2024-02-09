<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupPermissionModel extends Model
{
    protected $table            = 'groups_permissions';
    protected $returnType       = 'object';
    protected $allowedFields    = ['group_id', 'permission_id'];
}
