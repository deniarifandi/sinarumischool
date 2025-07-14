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

    public function __construct()
    {
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

    public function savePresensi(){
       $db = \Config\Database::connect(); // Connect to the database

// Get POST data
$nama     = $_POST['nama'];
$status   = $_POST['status'];
$longitude = $_POST['longitude'];
$latitude  = $_POST['latitude'];

// Debug output
echo "Status: $status<br>";
echo "Longitude: $longitude<br>";
echo "Latitude: $latitude<br>";
echo "Nama: $nama<br>";

// Build and run the query
$builder = $db->table('guru');
$builder->select('*');
$builder->where('guru_nama', $nama);
$query = $builder->get();

// Display the result
$results = $query->getResult();
echo "<pre>";
print_r($results);
echo "</pre>";
    }

}

