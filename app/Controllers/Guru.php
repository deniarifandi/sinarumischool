<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class Guru extends MyResourceController
{

    public $table = "Guru";
    public $title = "Personel";
    public $primaryKey = "guru_id";

    public $fieldList = [
        ['guru_nama','Personel`s Name'],
        // ['guru_username','Username'],
        // ['divisi_nama','Divison'],
        // ['guru_password','Password']
    ];

    public $selectList= [
            'Guru.guru_id',
            'Guru.guru_nama',
            'Guru.guru_username',
            // 'Divisi.divisi_nama'
        ];

    public $toSearch = 
    [
        'Guru.guru_nama',
        // 'Divisi.divisi_nama'
    ];

    public $where = [
      // 'Divisi.divisi_id' => ''
    ];


     public $joinTable = [
        // ['Divisi','Divisi.divisi_id = Guru.divisi_id','left']
        // ['kelompok', 'guru.guru_id = kelompok.guru_id','left']
        // ['kelompok', 'kelompok.guru_id = guru.guru_id','left']
    ];

       public $field = [
        ['text','guru_nama'],
        // ['select','divisi_id'],
        ['text','guru_username'],
        ['password','guru_password']
    ];


public $fieldName = [
        'Name',
        // 'Division',
        'Username',
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
        $this->model = new GuruModel();
        // $this->fieldOption[1] = $this->getdata('Divisi'); 
        if (session()->get('guru_id') != 0) {
            $this->where = ['Guru.guru_id' => session()->get('guru_id')];
        }
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function print(){
        
        $db = \Config\Database::connect();
        $builder = $db->table('Guru');
        $builder->select('*');
        // $builder->where('divisi_id', 1);
        $builder->where('deleted_at', null);
        $query = $builder->get();
        // print_r($query->getResult());
        return view('/report/guru_print',['data' => $query->getResult()]);

    }

}
