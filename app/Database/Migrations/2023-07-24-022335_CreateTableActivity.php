<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableActivity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'activity_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'activity_note' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'default'    => null
            ],
            'created_at' => [
                'type'  => 'datetime',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('activity_record');
    }

    public function down()
    {
        //
    }
}
