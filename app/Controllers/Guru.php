<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class Guru extends MyResourceController
{

    public $table = "Guru";
    public $title = "Teacher";
    public $primaryKey = "guru_id";

    public $fieldList = [
        ['guru_nama','Teacher`s Name'],
        ['guru_username','Username']
        // ['guru_password','Password']
    ];

    public $selectList= [
            'Guru.guru_id',
            'Guru.guru_nama',
            'Guru.guru_username'
        ];

    public $toSearch = 
    [
        'Guru.guru_nama'
    ];


     public $joinTable = [
        // ['kelompok', 'guru.guru_id = kelompok.guru_id','left']
        // ['kelompok', 'kelompok.guru_id = guru.guru_id','left']
    ];

       public $field = [
        ['text','guru_nama'],
        ['text','guru_username'],
        ['password','guru_password']
    ];


public $fieldName = [
        'Nama Guru',
        'Username',
        'Password'
    ];

public $fieldOption = [
  ['noOption'],
  ['noOption'],
  ['noOption']
];

    public $dataToShow = [];

    public function __construct()
    {
        $this->model = new GuruModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function print(){
        
        $db = \Config\Database::connect();
        $builder = $db->table('Guru');
        $builder->select('*');
        $builder->where('deleted_at', null);
        $query = $builder->get();

        return view('/report/guru_print',['data' => $query->getResult()]);

    }

}
