<?php

namespace App\Controllers;
use App\Models\GuruModel;
use Config\Database;

class Home extends BaseController
{
    public function index()
    {   
        $session = session();
        $model = new GuruModel();

        $username = session()->get('username');
        // echo $username;

        $builder = Database::connect()->table('Guru');
        $builder->select('Guru.*, Kelompok.*');
        $builder->join('Kelompok','Kelompok.guru_id = Guru.guru_id','left');
        $builder->where('guru_username',$username);

        // print_r($builder->get()->getResult());
        $data = $builder->get()->getResult();
        // echo json_encode($data[0]->kelompok_nama);
        return view('dashboard.php',['data' => $data[0]]);
    }
     public function blank()
    {   
        return view('blank.php');
    }

    public function login(){
        return view('login.php');
    }

    public function loginAuth()
    {
        $session = session();
        $model = new GuruModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('guru_username', $username)->first();

        if ($user && password_verify($password, $user['guru_password'])) {
            $session->set([
                'user_id' => $user['guru_id'], 
                'username' => $user['guru_username'], 
                'logged_in' => true]);
            return redirect()->to('/');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
