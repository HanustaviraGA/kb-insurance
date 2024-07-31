<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\User;

class AuthController extends BaseController
{
    public function index(){
        if($this->session->has('isLoggedIn')){
            return redirect()->to('/dashboard');
        }
        return view('login');
    }

    public function login_aksi(){
        // Ambil data
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        // Buat model user
        $userModel = new User();
        // Cek user di database
        $user = $userModel->where('email', $email)->first();
        if($user && password_verify($password, $user['password'])){
            $this->session->set([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'isLoggedIn' => true
            ]);
            $response = [
                'status' => 200,
                'success' => true
            ];
            return $this->response->setJSON($response);
        }else{
            $response = [
                'status' => 401,
                'success' => false
            ];
            return $this->response->setJSON($response);
        }
    }

    public function logout_aksi(){
        $this->session->destroy();
        $response = [
            'status' => 200,
            'success' => true
        ];
        return $this->response->setJSON($response);
    }
}
