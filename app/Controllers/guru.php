<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class guru extends MyResourceController
{

    public $table = "guru";
    public $title = "guru";
    public $primaryKey = "guru_id";
    public $fieldList = [
        ['guru_nama','Nama Guru']
    ];

    public $field = [
        ['text','guru_nama']
];

public $fieldName = [
        'Nama Guru'
    ];

public $fieldOption = [
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new GuruModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table('guru')
            ->select('guru.*')
            ->where('guru.deleted_at', null);       

        // print_r($builder->get()->getResult());

        $datatable = new Datatable();

        return $datatable->generate($builder, 'guru.guru_id',[
            'guru.guru_nama'
        ]);
    }

}
