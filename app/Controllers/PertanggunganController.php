<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use \Hermawan\DataTables\DataTable;
use App\Models\User;
use App\Models\JenisPertanggungan;
use App\Models\ResikoJenisPertanggungan;
use App\Models\Premi;
use App\Models\DetailPremi;

class PertanggunganController extends BaseController
{
    public function init_table()
    {
        $user = new User();
        $user->select('id, name, email, created_at');
        return DataTable::of($user)->addNumbering()->toJson();
    }

    public function create(){
        $postData = $this->request->getPost();
        // Cek interval tahun
        $interval = $this->check_interval($postData['periode_pertanggungan']);
        // Cek nominal premi
        $banjir = false;
        $gempa = false;
        if(isset($postData['banjir']) && $postData['banjir'] == '1'){
            $banjir = true;
        }
        if(isset($postData['gempa']) && $postData['gempa'] == '1'){
            $gempa = true;
        }
        $premi = $this->count_premi([
            'harga_pertanggungan' => (float)$postData['harga_pertanggungan'],
            'tahun' => $interval['tahun'],
            'jenis_pertanggungan' => $postData['jenis_pertanggungan'],
            'banjir' => $banjir,
            'gempa' => $gempa
        ]);
        
        try{
            $this->db->transStart();

        }catch(\Exception $e){
            
        }
    }

    public function update(){
        
    }

    public function delete(){
        
    }

    // Utility
    public function check_interval($date){
        // Cek tanggal kurang dari 1 tahun atau tidak
        list($startDateStr, $endDateStr) = explode(' - ', $date);
        $startDate = \DateTime::createFromFormat('m/d/Y', $startDateStr)->format('Y-m-d');
        $endDate = \DateTime::createFromFormat('m/d/Y', $endDateStr)->format('Y-m-d');
        $startDateObj = new \DateTime($startDate);
        $endDateObj = new \DateTime($endDate);
        $interval = $startDateObj->diff($endDateObj);
        $months = $interval->y * 12 + $interval->m;
        // Kalau lebih dari 12 bulan / 1 tahun, ambil 1 tahun pertama saja
        if ($months > 12 && $months < 12) {
            $endDateObj = clone $startDateObj;
            $endDateObj->add(new DateInterval('P12M')); 
            $endDate = $endDateObj->format('Y-m-d');
            $months = 12;
        }
        return [
            'periode_awal_pertanggungan' => $startDate,
            'periode_akhir_pertanggungan' => $endDate,
            'bulan' => $months,
            'tahun' => $months / 12
        ];
    }

    public function count_premi($data = []){
        // Total Premi
        $total_premi = 0;
        // Harga Pertanggungan
        $harga_pertanggungan = $data['harga_pertanggungan'];

        // Premi Kendaraan
        $tahun = $data['tahun'];
        $jenis_pertanggungan = $data['jenis_pertanggungan']; // 1 : Comprehensive, 2 : Total Loss Only
        $query_jenis = new JenisPertanggungan();
        $query_jenis->where('id_jenis_pertanggungan', $jenis_pertanggungan);
        $rate_jenis = $query_jenis->first()['rate_jenis_pertanggungan'];
        $premi_kendaraan = $tahun * $harga_pertanggungan * $rate_jenis;
        $total_premi += $premi_kendaraan;

        // Premi Resiko Pertanggungan
        $premi_banjir = 0;
        $premi_gempa = 0;

        if(isset($data['banjir'])){
            $query_banjir = new ResikoJenisPertanggungan();
            $query_banjir->where('id_jenis_pertanggungan', $jenis_pertanggungan)->where('nama_resiko_jenis_pertanggungan', 'Banjir');
            $rate_banjir = $query_banjir->first()['rate_resiko_jenis_pertanggungan'];
            if($rate_banjir == 0){
                $premi_banjir = $tahun * $harga_pertanggungan * 0;
            }else{
                $premi_banjir = $tahun * $harga_pertanggungan * $rate_banjir;
            }
            $total_premi += $premi_banjir;
        }
        if(isset($data['gempa'])){
            $query_gempa = new ResikoJenisPertanggungan();
            $query_gempa->where('id_jenis_pertanggungan', $jenis_pertanggungan)->where('nama_resiko_jenis_pertanggungan', 'Gempa');
            $rate_gempa = $query_gempa->first()['rate_resiko_jenis_pertanggungan'];
            if($rate_gempa == 0){
                $premi_gempa = $tahun * $harga_pertanggungan * 0;
            }else{
                $premi_gempa = $tahun * $harga_pertanggungan * $rate_gempa;
            }
            $total_premi += $premi_gempa;
        }

        return [
            'premi_kendaraan' => $premi_kendaraan,
            'premi_banjir' => $premi_banjir,
            'premi_gempa' => $premi_gempa,
            'total_premi' => $total_premi
        ];
    }
}
