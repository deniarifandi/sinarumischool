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

    public function showform(){
        return view('/presence/form');
    }

}
