<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGroupsPermissions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'group_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'permission_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        $this->forge->addForeignKey('group_id', 'groups', 'id', 'CASCADE', 'CASCADE', 'fk_group_id_groups');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE', 'fk_permission_id_permissions');

        $this->forge->createTable('groups_permissions');
    }

    public function down()
    {
        $this->forge->dropForeignKey('groups_permissions', 'fk_permission_id_permissions');
        $this->forge->dropForeignKey('groups_permissions', 'fk_group_id_groups');

        $this->forge->dropTable('groups_permissions');
    }
}
