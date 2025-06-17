<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class guru extends MyResourceController
{

    public $table = "guru";
    public $title = "Teacher";
    public $primaryKey = "guru_id";

    public $fieldList = [
        ['guru_nama','Teacher`s Name'],
        ['guru_username','Username']
        // ['guru_password','Password']
    ];

    public $selectList= [
            'guru.guru_id',
            'guru.guru_nama',
            'guru.guru_username'
        ];

    public $toSearch = 
    [
        'guru.guru_nama'
    ];


     public $joinTable = [
        // ['kelompok', 'guru.guru_id = kelompok.guru_id','left']
        // ['kelompok', 'kelompok.guru_id = guru.guru_id','left']
    ];

       public $field = [
        ['text','guru_nama'],
        ['text','guru_username'],
        ['text','guru_password']
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

    // public function data(){
    //     $builder = Database::connect()->table('guru')
    //         ->select('guru.*')
    //         ->where('guru.deleted_at', null);       

    //     // print_r($builder->get()->getResult());

    //     $datatable = new Datatable();

    //     return $datatable->generate($builder, 'guru.guru_id',[
    //         'guru.guru_nama'
    //     ]);
    // }

}
