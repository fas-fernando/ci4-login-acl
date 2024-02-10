<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableGroupsUsers extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        
        $this->forge->addForeignKey('group_id', 'groups', 'id', 'CASCADE', 'CASCADE', 'fk_group_id_groups_u');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'fk_user_id_users');

        $this->forge->createTable('groups_users');
    }

    public function down()
    {
        $this->forge->dropForeignKey('groups_users', 'fk_user_id_users');
        $this->forge->dropForeignKey('groups_users', 'fk_group_id_groups_u');

        $this->forge->dropTable('groups_users');
    }
}
