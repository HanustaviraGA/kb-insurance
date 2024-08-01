<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class DashboardController extends BaseController
{
    public function index()
    {
        if($this->session->has('isLoggedIn')){
            $sidebar = [
                [
                    'menu_judul' => 'Dashboard',
                    'menu_kode' => 'dashboard'
                ],
                [
                    'menu_judul' => 'Pertanggungan',
                    'menu_kode' => 'pertanggungan'
                ]
            ];
            return view('backoffice/dashboard', compact('sidebar'));
        }else{
            return view('login');
        }
    }

    // Utility
    public function load_page() {
        $page = $this->request->getPost('destination');
        if($this->request->getPost('data')){
            $data = $this->request->getPost('data');
        }else{
            $data = [];
        }
        $view = view('backoffice/'.$page.'/index.php', $data);
        $base64 = base64_encode($view);
        return $this->response->setJSON(['page' => $base64]);
    }
}
