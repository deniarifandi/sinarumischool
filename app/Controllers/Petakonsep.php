<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\PetakonsepModel;
use App\Libraries\datatable;
use Config\Database;

class Petakonsep extends MyResourceController
{

    public $table = "petakonsep";
    public $title = "Petakonsep";
    public $primaryKey = "petakonsep_id";
    public $fieldList = [
        ['judul','Judul Petakonsep'], 
        ['url','URL']        
    ];

     public $where = [
      
    ];


    public $selectList= [
            'petakonsep.*',
        ];

    public $toSearch = 
    [
        'guru.guru_nama'
    ];


    public $field = [
        ['text','judul'], 
        ['file','url'],
];

public $fieldName = [
        'judul', 
        'url'
    ];

public $fieldOption = [
  ['noOption'], 
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new PetakonsepModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    // public function data(){
    //     $builder = Database::connect()->table($this->table)
    //     ->select('petakonsep.*')
    //     ->where('petakonsep.deleted_at',NULL);

    //     $datatable = new Datatable();

    //     return $datatable->generate($builder, 'petakonsep.petakonsep_id',[
    //         'petakonsep.judul'
    //     ]);
    // }

}
