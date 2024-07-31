<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResikoJenisPertanggunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_resiko_jenis_pertanggungan' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_resiko_jenis_pertanggungan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'id_jenis_pertanggungan' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
            'rate_resiko_jenis_pertanggungan' => [
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
        $this->forge->addKey('id_resiko_jenis_pertanggungan', true);
        $this->forge->createTable('resiko_jenis_pertanggungan');
    }

    public function down()
    {
        $this->forge->dropTable('resiko_jenis_pertanggungan');
    }
}
