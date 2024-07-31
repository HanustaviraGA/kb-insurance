<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ResikoJenisPertanggunganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Comprehensive
            [
                'id_resiko_jenis_pertanggungan'      => 1,
                'nama_resiko_jenis_pertanggungan'    => 'Banjir',
                'id_jenis_pertanggungan'             => 1,
                'rate_resiko_jenis_pertanggungan'    => 0.0005,
            ],
            [
                'id_resiko_jenis_pertanggungan'      => 2,
                'nama_resiko_jenis_pertanggungan'    => 'Gempa',
                'id_jenis_pertanggungan'             => 1,
                'rate_resiko_jenis_pertanggungan'    => 0.0002,
            ],

            // Total Loss Only
            [
                'id_resiko_jenis_pertanggungan'      => 3,
                'nama_resiko_jenis_pertanggungan'    => 'Banjir',
                'id_jenis_pertanggungan'             => 2,
                'rate_resiko_jenis_pertanggungan'    => 0,
            ],
            [
                'id_resiko_jenis_pertanggungan'      => 4,
                'nama_resiko_jenis_pertanggungan'    => 'Gempa',
                'id_jenis_pertanggungan'             => 2,
                'rate_resiko_jenis_pertanggungan'    => 0,
            ],
        ];

        $this->db->table('resiko_jenis_pertanggungan')->insertBatch($data);
    }
}
