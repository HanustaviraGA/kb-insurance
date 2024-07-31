<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePremiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_premi' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_nasabah' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null' => false,
            ],
            'periode_awal_pertanggungan' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'periode_akhir_pertanggungan' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jenis_pertanggungan' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'premi_kendaraan' => [
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
        $this->forge->addKey('id_premi', true);
        $this->forge->createTable('premi');
    }

    public function down()
    {
        $this->forge->dropTable('premi');
    }
}
