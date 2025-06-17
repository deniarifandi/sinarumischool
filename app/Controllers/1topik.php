<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TopikModel;
use App\Libraries\datatable;
use Config\Database;

class topik extends MyResourceController
{

    public $table = "topik";
    public $title = "Topik";
    public $primaryKey = "topik_id";
    public $fieldList = [
        ['topik_isi','Nama Topik']
    ];

    public $field = [
        ['text','topik_isi']
];

public $fieldName = [
        'Nama Topik'
    ];

public $fieldOption = [
  ['noOption'],
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new TopikModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('topik.*')
        ->where('topik.deleted_at',NULL);

        $datatable = new Datatable();

        return $datatable->generate($builder, 'topik.topik_id',[
            'topik.topik_id',
            'topik.topik_isi'
        ]);
    }

}
