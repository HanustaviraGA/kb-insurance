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
use CodeIgniter\RESTful\ResourceController;
use Dompdf\Dompdf;
use Mpdf\Mpdf;

class PertanggunganController extends BaseController
{
    public function init_table(){
        $premi = new Premi();
        $premi->select('premi.id_premi, premi.nama_nasabah, premi.harga_pertanggungan, jp.nama_jenis_pertanggungan, u.name');
        $premi->join('users u', 'u.id = premi.created_by');
        $premi->join('jenis_pertanggungan jp', 'jp.id_jenis_pertanggungan = premi.jenis_pertanggungan');
        $premi->where('premi.deleted_at', NULL);
        $premi->orderBy('premi.id_premi', 'DESC');
        return DataTable::of($premi)->addNumbering()->toJson();
    }

    public function create(){
        $postData = $this->request->getPost();
        // Cek interval tahun
        $interval = $this->check_interval($postData['periode_pertanggungan']);
        // Cek nominal premi
        $banjir = false;
        $gempa = false;
        if(isset($postData['banjir']) && $postData['banjir'] == 'on'){
            $banjir = true;
        }
        if(isset($postData['gempa']) && $postData['gempa'] == 'on'){
            $gempa = true;
        }
        $premi = $this->count_premi([
            'harga_pertanggungan' => (float)$postData['harga_pertanggungan'],
            'tahun' => $interval['tahun'],
            'jenis_pertanggungan' => $postData['jenis_pertanggungan'],
            'banjir' => $banjir,
            'gempa' => $gempa
        ]);

        // Jadikan satu data
        $data_premi = [
            'nama_nasabah' => $postData['nama_nasabah'],
            'pertanggungan_kendaraan' => $postData['pertanggungan_kendaraan'],
            'periode_awal_pertanggungan' => $interval['periode_awal_pertanggungan'],
            'periode_akhir_pertanggungan' => $interval['periode_akhir_pertanggungan'],
            'harga_pertanggungan' => (float)$postData['harga_pertanggungan'],
            'jenis_pertanggungan' => (int)$postData['jenis_pertanggungan'],
            'premi_kendaraan' => $premi['premi_kendaraan'],
            'total_premi' => (float)$premi['total_premi'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->session->get('id'),
        ];

        try{
            $this->db->transStart();
            // Masukkan data premi
            $q_premi = new Premi();
            $q_premi->insert($data_premi);
            $insertedID = $q_premi->insertID();
            // Masukkan data detail premi
            $data_detail_premi = [];
            if($premi['id_premi_banjir'] !== 0){
                $data_detail_premi[] = [
                    'id_premi' => $insertedID,
                    'id_resiko_jenis_pertanggungan' => $premi['id_premi_banjir'],
                    'nominal_resiko_premi_jenis_pertanggungan' => $premi['premi_banjir'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->get('id'),
                ];
            }
            if($premi['id_premi_gempa'] !== 0){
                $data_detail_premi[] = [
                    'id_premi' => $insertedID,
                    'id_resiko_jenis_pertanggungan' => $premi['id_premi_gempa'],
                    'nominal_resiko_premi_jenis_pertanggungan' => $premi['premi_gempa'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->get('id'),
                ];
            }
            $q_detail_premi = new DetailPremi();
            $q_detail_premi->insertBatch($data_detail_premi);
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }
            return $this->response->setStatusCode(200)->setJSON(['success' => true]);
        }catch(\Exception $e){
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function read(){
        $postData = $this->request->getRawInput();
        $id_premi = $postData['id'];
        // Data premi
        $premi = new Premi();
        $premi->select('*');
        $premi->where('premi.deleted_at', NULL);
        $premi->where('premi.id_premi', $id_premi);
        $data = $premi->first();
        // Detail premi
        $detail_premi = new DetailPremi();
        $detail_premi->join('resiko_jenis_pertanggungan rjp', 'rjp.id_resiko_jenis_pertanggungan = detail_premi.id_resiko_jenis_pertanggungan');
        $detail_premi->select('*');
        $detail_premi->where('detail_premi.deleted_at', NULL);
        $detail_premi->where('detail_premi.id_premi', $id_premi);
        $data['detail_premi'] = $detail_premi->findAll();
        if($data){
            return $this->response->setStatusCode(200)->setJSON(['success' => true, 'result' => $data]);
        }else{
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Data not found']);
        }
    }

    public function update(){
        $postData = $this->request->getRawInput();
        $id_premi = $postData['id_premi'];
        // Cek interval tahun
        $interval = $this->check_interval($postData['periode_pertanggungan']);
        // Cek nominal premi
        $banjir = false;
        $gempa = false;
        if(isset($postData['banjir']) && $postData['banjir'] == 'on'){
            $banjir = true;
        }
        if(isset($postData['gempa']) && $postData['gempa'] == 'on'){
            $gempa = true;
        }
        $premi = $this->count_premi([
            'harga_pertanggungan' => (float)$postData['harga_pertanggungan'],
            'tahun' => $interval['tahun'],
            'jenis_pertanggungan' => $postData['jenis_pertanggungan'],
            'banjir' => $banjir,
            'gempa' => $gempa
        ]);

        // Jadikan satu data
        $data_premi = [
            'nama_nasabah' => $postData['nama_nasabah'],
            'pertanggungan_kendaraan' => $postData['pertanggungan_kendaraan'],
            'periode_awal_pertanggungan' => $interval['periode_awal_pertanggungan'],
            'periode_akhir_pertanggungan' => $interval['periode_akhir_pertanggungan'],
            'harga_pertanggungan' => (float)$postData['harga_pertanggungan'],
            'jenis_pertanggungan' => (int)$postData['jenis_pertanggungan'],
            'premi_kendaraan' => $premi['premi_kendaraan'],
            'total_premi' => (float)$premi['total_premi'],
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->get('id'),
        ];

        try{
            $this->db->transStart();
            // Masukkan data premi
            $q_premi = new Premi();
            $q_premi->set($data_premi)->where('id_premi', $id_premi)->update();
            $insertedID = $id_premi;
            // Masukkan data detail premi
            $del_detail_premi = new DetailPremi();
            $del_detail_premi->where('id_premi', $insertedID)->delete();
            $data_detail_premi = [];
            if($premi['id_premi_banjir'] !== 0){
                $data_detail_premi[] = [
                    'id_premi' => $insertedID,
                    'id_resiko_jenis_pertanggungan' => $premi['id_premi_banjir'],
                    'nominal_resiko_premi_jenis_pertanggungan' => $premi['premi_banjir'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->get('id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->get('id'),
                ];
            }
            if($premi['id_premi_gempa'] !== 0){
                $data_detail_premi[] = [
                    'id_premi' => $insertedID,
                    'id_resiko_jenis_pertanggungan' => $premi['id_premi_gempa'],
                    'nominal_resiko_premi_jenis_pertanggungan' => $premi['premi_gempa'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->get('id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->get('id'),
                ];
            }
            $q_detail_premi = new DetailPremi();
            $q_detail_premi->insertBatch($data_detail_premi);
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }
            return $this->response->setStatusCode(200)->setJSON(['success' => true]);
        }catch(\Exception $e){
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete(){
        $postData = $this->request->getRawInput();
        $id = $postData['id'];
        try{
            $this->db->transStart();
            // Soft delete data premi
            $data = [
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $this->session->get('id'),
            ];
            $q_premi = new Premi();
            $q_premi->set($data)->where('id_premi', $id)->update();
            $this->db->transComplete();
            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Transaction failed');
            }
            return $this->response->setStatusCode(200)->setJSON(['success' => true]);
        }catch(Exception $e){
            return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function print($id_premi = NULL){
        // Ambil data premi
        $q_premi = new Premi();
        $q_premi->select('premi.*, jp.nama_jenis_pertanggungan, jp.rate_jenis_pertanggungan');
        $q_premi->join('jenis_pertanggungan jp', 'jp.id_jenis_pertanggungan = premi.jenis_pertanggungan');
        $q_premi->where('id_premi', $id_premi);
        $premi = $q_premi->first();

        $startDate = \DateTime::createFromFormat('Y-m-d', $premi['periode_awal_pertanggungan'])->format('d/m/Y');
        $endDate = \DateTime::createFromFormat('Y-m-d', $premi['periode_akhir_pertanggungan'])->format('d/m/Y');

        // Ambil data detail premi
        $q_detail_premi = new DetailPremi();
        $q_detail_premi->select('detail_premi.*, rjp.nama_resiko_jenis_pertanggungan, rjp.rate_resiko_jenis_pertanggungan');
        $q_detail_premi->join('resiko_jenis_pertanggungan rjp', 'rjp.id_resiko_jenis_pertanggungan = detail_premi.id_resiko_jenis_pertanggungan');
        $q_detail_premi->where('id_premi', $id_premi);
        $detail_premi = $q_detail_premi->findAll();
        $html_detail = '';
        $name_resiko = [];
        foreach($detail_premi as $key => $val){
            array_push($name_resiko, $val['nama_resiko_jenis_pertanggungan']);
            $html_detail .= '<p><span class="label">'.$val['nama_resiko_jenis_pertanggungan'].'</span>: '.number_format($val['nominal_resiko_premi_jenis_pertanggungan']).' ('.number_format($premi['harga_pertanggungan']).' x '.$val['rate_resiko_jenis_pertanggungan'].')</p>';
        }

        $html = '';
        $html .= '
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Insurance Information</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    .container {
                        width: 100%;
                        max-width: 600px;
                        margin: auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                    }
                    .header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 20px;
                    }
                    .header img {
                        height: 50px;
                    }
                    .section {
                        margin-bottom: 20px;
                    }
                    .section h2 {
                        font-size: 16px;
                        margin-bottom: 10px;
                    }
                    .section p {
                        margin: 5px 0;
                    }
                    .label {
                        display: inline-block;
                        width: 220px; /* Increase width for better text fit */
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <img src="https://i.imgur.com/aPhF1VE.png" alt="KB Insurance Indonesia">
                    </div>
                    <div class="section">
                        <h2>General Information</h2>
                        <p><span class="label">Nama Tertanggung</span>: '.$premi['nama_nasabah'].'</p>
                        <p><span class="label">Periode Pertanggungan</span>: '.$startDate.' - '.$endDate.'</p>
                        <p><span class="label">Pertanggungan / Kendaraan</span>: '.$premi['pertanggungan_kendaraan'].'</p>
                        <p><span class="label">Harga Pertanggungan</span>: '.number_format($premi['harga_pertanggungan']).'</p>
                    </div>
                    <div class="section">
                        <h2>Coverage Information</h2>
                        <p><span class="label">Jenis Pertanggungan</span>: '.$premi['nama_jenis_pertanggungan'].'</p>
                        <p><span class="label">Risiko Pertanggungan</span>: '.implode(', ', $name_resiko).'</p>
                    </div>
                    <div class="section">
                        <h2>Premium Calculation</h2>
                        <p><span class="label">Periode Pertanggungan</span>: '.$startDate.' - '.$endDate.'</p>
                        <p><span class="label">Premi Kendaraan</span>: '.number_format($premi['premi_kendaraan']).' ('.number_format($premi['harga_pertanggungan']).' x '.$premi['rate_jenis_pertanggungan'].')</p>
                        '.$html_detail.'
                        <p><strong>Total Premi</strong>: '.number_format($premi['total_premi']).'</p>
                    </div>
                </div>
            </body>
        </html>';

        $this->print_utility(['html' => $html]);
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
        $jenis_pertanggungan_data = $query_jenis->first();
        $rate_jenis = $jenis_pertanggungan_data['rate_jenis_pertanggungan'];
        $premi_kendaraan = $tahun * $harga_pertanggungan * $rate_jenis;
        $total_premi += $premi_kendaraan;

        // Premi Resiko Pertanggungan
        $premi_banjir = 0;
        $premi_gempa = 0;
        $id_premi_banjir = 0;
        $id_premi_gempa = 0;

        if ($data['banjir']) {
            $query_banjir = new ResikoJenisPertanggungan();
            $query_banjir->where('id_jenis_pertanggungan', $jenis_pertanggungan)
                        ->where('nama_resiko_jenis_pertanggungan', 'Banjir');
            $banjir_data = $query_banjir->first();
            if ($banjir_data) {
                $rate_banjir = $banjir_data['rate_resiko_jenis_pertanggungan'];
                $id_premi_banjir = $banjir_data['id_resiko_jenis_pertanggungan'];
                $premi_banjir = $tahun * $harga_pertanggungan * $rate_banjir;
                $total_premi += $premi_banjir;
            }
        }

        if ($data['gempa']) {
            $query_gempa = new ResikoJenisPertanggungan();
            $query_gempa->where('id_jenis_pertanggungan', $jenis_pertanggungan)
                        ->where('nama_resiko_jenis_pertanggungan', 'Gempa');
            $gempa_data = $query_gempa->first();
            if ($gempa_data) {
                $rate_gempa = $gempa_data['rate_resiko_jenis_pertanggungan'];
                $id_premi_gempa = $gempa_data['id_resiko_jenis_pertanggungan'];
                $premi_gempa = $tahun * $harga_pertanggungan * $rate_gempa;
                $total_premi += $premi_gempa;
            }
        }

        $response = [
            'premi_kendaraan' => $premi_kendaraan,
            'premi_banjir' => $premi_banjir,
            'id_premi_banjir' => $id_premi_banjir,
            'premi_gempa' => $premi_gempa,
            'id_premi_gempa' => $id_premi_gempa,
            'total_premi' => $total_premi
        ];

        return $response;
    }


    public function print_utility($data){
        $filename = date('y-m-d-H-i-s'). '-insurance';
        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        // load HTML content
        $dompdf->loadHtml(view('backoffice/pertanggungan/pdf', $data));
        $dompdf->set_option('isRemoteEnabled', true);
        // (optional) setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        // render html as PDF
        $dompdf->render();
        // output the generated pdf
        $dompdf->stream($filename);
    }
}
