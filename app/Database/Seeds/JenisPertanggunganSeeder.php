<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JenisPertanggunganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id_jenis_pertanggungan'      => 1,
                'nama_jenis_pertanggungan'    => 'Comprehensive',
                'rate_jenis_pertanggungan'    => 0.0015,
            ],
            [
                'id_jenis_pertanggungan'      => 2,
                'nama_jenis_pertanggungan'    => 'Total Loss Only',
                'rate_jenis_pertanggungan'    => 0.005,
            ],
        ];

        $this->db->table('jenis_pertanggungan')->insertBatch($data);
    }
}
