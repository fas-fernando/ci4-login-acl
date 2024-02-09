<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupPermissionModel extends Model
{
    protected $table            = 'groups_permissions';
    protected $returnType       = 'object';
    protected $allowedFields    = ['group_id', 'permission_id'];

    public function getPermissionsGroup(int $group_id, int $quantity_page)
    {
        $attr = [
            'groups_permissions.id',
            'groups.id AS group_id',
            'permissions.id AS permission_id',
            'permissions.name'
        ];

        $query = $this->select($attr)
            ->join('groups', 'groups.id = groups_permissions.group_id')
            ->join('permissions', 'permissions.id = groups_permissions.permission_id')
            ->where('groups_permissions.group_id', $group_id)
            ->groupBy('permissions.name')
            ->paginate($quantity_page);

        return $query;
    }
}
