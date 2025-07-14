<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\PresensiModel;
use App\Libraries\datatable;
use Config\Database;

class Presensi extends MyResourceController
{

    public $table = "Presensi";
    public $title = "Personel";
    public $primaryKey = "guru_id";

    public $fieldList = [
        ['guru_nama','Name'],
        ['divisi_nama','Divison'],
        ['guru_jabatan','Position']
// ['guru_password','Password']
    ];

    public $selectList= [
        'Presensi.*',
        'Divisi.divisi_nama'
    ];

    public $toSearch = 
    [
        'Presensi.guru_nama',
        'Divisi.divisi_nama'
    ];

    public $where = [
        'Divisi.divisi_id !=' => '0'
    ];


    public $joinTable = [
        ['Divisi','Divisi.divisi_id = Presensi.divisi_id','left']
    ];

    public $field = [
        ['text','guru_nama'],
        ['select','divisi_id'],
        ['text','guru_username'],
        ['text','guru_jabatan'],
        ['password','guru_password']
    ];


    public $fieldName = [
        'Name',
        'Division',
        'Username',
        'Jabatan',
        'Password'
    ];

    public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption'],

    ];

    public $dataToShow = [];

    public $db;

    public function __construct()
    {   
        $this->db = \Config\Database::connect(); 
        $this->model = new PresensiModel();
        $this->fieldOption[1] = $this->getdata('Divisi'); 
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function print(){

        $db = \Config\Database::connect();
        $builder = $db->table('Presensi');
        $builder->select('*');
        $builder->where('deleted_at', null);

        $query = $builder->get();

        return view('/report/guru_print',['data' => $query->getResult()]);

    }

    public function showForm(){
        return view('/presence/form');
    }

    public function showStatus(){
        return view('/presence/status');
    }

    public function getGuruId(){
        $this->db = \Config\Database::connect(); 

        $nama     = trim($_POST['nama']);
        $status   = $_POST['status'];
        $today = date('Y-m-d');
        $builder = $this->db->table('Guru');
        $builder->select('Guru.*');
        $builder->where('guru_nama', $nama);
        $query = $builder->get();
        $results = $query->getResult();
        if (count($result > 0)) {
            return $results[0]->guru_id;
        }else{
            $result = "Staff Data not Found, contact Administrator";
            $code = 0;
             echo view('/presence/result.php',['result' => $result,'code' => $code]);
        }
        
    }

    public function cekPresensi(){
        $builder = $this->db->table('Presensidata');
        $builder->select('Presensidata.*');
        $builder->where('guru_id', $this->getGuruId());

        $query = $builder->get();
        $resultsPresensi = $query->getResult();

        return count($resultsPresensi);
    }

    public function savePresensi(){

        if ($this->cekPresensi() > 0) {
            $result = "It looks like you’ve already submitted your data for today. No need to submit again, everything has been recorded successfully. Thank you for staying consistent!";
            $code = 0;
        }else{
            
            $data = [
                'guru_id'          => $this->getGuruId(),
                'longitude'        => $_POST['longitude'],
                'latitude'         => $_POST['latitude'],
            ];

            $builder = $this->db->table('presensidata');
            $builder->insert($data);

            $result = "Your attendance has been successfully recorded for today. Thank you for checking in on time, we appreciate your punctuality and dedication.";
            $code = 1;
        }

        echo view('/presence/result.php',['result' => $result,'code' => $code]);
    }
}

