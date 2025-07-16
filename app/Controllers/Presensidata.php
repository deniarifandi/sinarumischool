<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\PresensidataModel;
use App\Libraries\datatable;
use Config\Database;

class Presensidata extends MyResourceController
{

    public $table = "Presensidata";
    public $title = "Attendance Data";
    public $primaryKey = "presensidata_id";

    public $fieldList = [
        ['guru_nama','Teacher`s Name'],
        ['presensidata_tanggal','Date'],
        ['presensistatus_nama','Status']

    ];

    public $selectList= [
        'Presensidata.*',
        'Presensistatus.*',
        'Guru.*'
    ];

    public $toSearch = 
    [
        'Presensidata.guru_id',
        'Guru.guru_nama'
    ];


    public $where = [
    ];


    public $joinTable = [
        ['Guru','Guru.guru_id = Presensidata.guru_id','left'],
        ['Presensistatus','Presensidata.status = Presensistatus.presensistatus_id','left']
    ];

    public $field = [
        ['select','guru_id'],
        ['select','status']
    ];


    public $fieldName = [
        'Name',
        'Status'
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
        $this->model = new PresensidataModel();
        
        $this->fieldOption[0] = $this->getdata('Guru'); 
        $this->fieldOption[1] = $this->getdata('Presensistatus'); 
        $this->where = ['Guru.guru_id' => session()->get('guru_id')];
        $this->dataToShow = $this->prepareDataToShow();
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
        if (count($results) > 0) {
            return $results[0]->guru_id;
        }else{
            $result = "Staff Data not Found, contact Administrator";
            $code = 0;
            $title = "Failed";
             echo view('/presence/result.php',[
                'result' => $result,
                'code' => $code,
                'title' => $title
            ]);
        }
    }

    public function cekPresensi(){
        $builder = $this->db->table('Personeldata');
        $builder->select('Personeldata.*');
        $builder->where('guru_id', $this->getGuruId());
        $builder->where('Personeldata_tanggal', date("Y-m-d"));

        $query = $builder->get();
        $resultsPersonel = $query->getResult();

        return count($resultsPersonel);
    }

    public function savePresensi(){

        if ($this->cekPresensi() > 0) {
            $result = "It looks like you’ve already submitted your data for today. No need to submit again, everything has been recorded successfully. Thank you for staying consistent!";
            $code = 0;
            $title = "Failed";
            
        }else{
            
            $data = [
                'guru_id'          => $this->getGuruId(),
                'longitude'        => $_POST['longitude'],
                'latitude'         => $_POST['latitude'],
                'status'           => $_POST['status']
            ];

            $builder = $this->db->table('Presensidata');
            $builder->insert($data);

              $title = "Success";
            $result = "Your attendance has been successfully recorded for today. Thank you for checking in on time, we appreciate your punctuality and dedication.";
            $code = 1;

           
            // echo ;
        }

        echo view('/presence/result.php',[
            'result' => $result,
            'code' => $code,
            'title' => $title
        ]);
    }

}

