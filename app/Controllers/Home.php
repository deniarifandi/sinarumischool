<?php

namespace App\Controllers;
use App\Models\GuruModel;
use Config\Database;


class Home extends BaseController
{   
    public $session;

    public function __construct()
    {   
       $this->session = session();
    }

    public function index()
    {   
        
        $model = new GuruModel();

        $username = $this->session->get('username');
        $guru_nama = $this->session->get('nama');
        $guru_id = $this->session->get('guru_id');
        // echo $guru_id;

        $builder = Database::connect()->table('Guru');
        $builder->select('Guru.*,Kelompok.*');
        $builder->join('Kelompok','Kelompok.guru_id = Guru.guru_id','inner');
        $builder->where('Guru.guru_id',$guru_id);

        $presence = $this->cekPresensi();

        $data = $builder->get()->getResult();
        // print_r($data);
        return view('dashboard.php',[
            'data' => $data[0], 
            'presence' => $presence,
            'nama' => $guru_nama
        ]);
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
        
        $model = new GuruModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('guru_username', $username)->first();

        if ($user && password_verify($password, $user['guru_password'])) {
            $this->session->set([
                'guru_id' => $user['guru_id'], 
                'nama' => $user['guru_nama'], 
                'username' => $user['guru_username'], 
                'logged_in' => true]);
        
            $db = \Config\Database::connect(); 
            $builder = $db->table('Gurudivisi');
            $builder->select('Gurudivisi.*');
            $builder->where('guru_id', $user['guru_id']);
            $query = $builder->get();
            $resultsDivisi = $query->getResult();
            print_r($resultsDivisi);
          
          if (!empty($resultsDivisi) && isset($resultsDivisi[0]->divisi_id)) {

                $this->session->set([
                    'divisi_id' => $resultsDivisi[0]->divisi_id
                ]);
            }

            // echo session()->get('gurudivisi_id');

            return redirect()->to('/');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

     public function cekPresensi(){
        $db = \Config\Database::connect(); 
        $session = session();
        $guru_id = session()->get('guru_id');
        // echo $guru_id;
        $builder = $db->table('Presensidata');
        $builder->select('Presensidata.*');
        $builder->where('guru_id', $guru_id);
        $builder->where('presensidata_tanggal', date("Y-m-d"));

        $query = $builder->get();
        $resultsPresensi = $query->getResult();
        // print_r($resultsPresensi);
        return count($resultsPresensi);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
