<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisPertanggunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_jenis_pertanggungan' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_jenis_pertanggungan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'rate_jenis_pertanggungan' => [
                'type' => 'DOUBLE',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_by' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_by' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
        ]);
        $this->forge->addKey('id_jenis_pertanggungan', true);
        $this->forge->createTable('jenis_pertanggungan');
    }

    public function down()
    {
        $this->forge->dropTable('jenis_pertanggungan');
    }
}
