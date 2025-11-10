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
        $builder->join('Kelompok','Kelompok.guru_id = Guru.guru_id','left');
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

    public function getdata($table){
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        $builder->select('*');
        $builder->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getResultArray();
        $indexedOnly = array_map('array_values', $result);
    
        return $indexedOnly;
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

    public function lupaabsen(){

        $db = \Config\Database::connect();
        $builder = $db->table('Personel');
        $builder->select('guru_id,guru_nama');
        $builder->where('deleted_at', null);
        
        $query = $builder->get();
        $result = $query->getResultArray();
        $indexedOnly = array_map('array_values', $result);

        
        return view('presence/lupaabsen', ['guru' => $result]);
    }

    public function cekPresensi2($guru_id,$tanggal){
        $db = \Config\Database::connect();
        $builder = $db->table('Presensidata');
        $builder->select('Presensidata.*');
        $builder->where('guru_id', $guru_id);
        $builder->where('Presensidata_tanggal',$tanggal);

        $query = $builder->get();
        $resultsPersonel = $query->getResult();

        return count($resultsPersonel);
    }

   public function lupaabsenstore()
{
    $db = \Config\Database::connect();
    $builder = $db->table('Presensidata');

    $tanggal = $this->request->getPost('presensidata_tanggal');
    $datetime = $tanggal . ' 07:39:00';

     $data = [
            'guru_id' => $this->request->getPost('guru_id'),
            'longitude' => $this->request->getPost('longitude'),
            'latitude' => $this->request->getPost('latitude'),
            'presensidata_tanggal' => $datetime,
            'status' => $this->request->getPost('status'),
            'created_at' => $datetime
        ];

     if ($this->cekPresensi2($this->request->getPost('guru_id'),$tanggal) > 0) {
        $result = "It looks like you’ve already submitted your data for today. No need to submit again, everything has been recorded successfully. Thank you for staying consistent!";
        $code = 1;
        $title = "Already Submit";

        session()->setFlashdata('success', $result);
         return redirect()->to('/lupaabsen');

    }else{

        $builder = $db->table('Presensidata');

        $title = "Success";
        $result = "Your attendance has been successfully recorded for today. Thank you for checking in on time, we appreciate your punctuality and dedication.";
        $code = 1;

         try {
        // Try inserting data
            if ($builder->insert($data)) {
                session()->setFlashdata('success', $result);
                 return redirect()->to('/lupaabsen');
            } else {
                session()->setFlashdata('error', 'Gagal menyimpan data presensi.');
                 return redirect()->to('/lupaabsen');
            }

        } catch (\Exception $e) {
            // Catch any database or runtime error
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
             return redirect()->to('/lupaabsen');
        }

        return redirect()->to('/lupaabsen');

    }

    
   
}
}
