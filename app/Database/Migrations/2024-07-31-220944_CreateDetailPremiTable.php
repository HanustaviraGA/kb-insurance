<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPremiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail_premi' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_premi' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
            'id_resiko_jenis_pertanggungan' => [
                'type' => 'INT',
                'null' => false,
                'unsigned' => true,
                'constraint' => 5
            ],
            'nominal_premi_jenis_pertanggungan' => [
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
        $this->forge->addKey('id_detail_premi', true);
        $this->forge->createTable('detail_premi');
    }

    public function down()
    {
        $this->forge->dropTable('detail_premi');
    }
}
