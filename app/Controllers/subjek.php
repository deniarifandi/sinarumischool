<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\SubjekModel;
use App\Libraries\datatable;
use Config\Database;

class subjek extends MyResourceController
{

    public $table = "subjek";
    public $title = "Subjek";
    public $primaryKey = "subjek_id";
    public $fieldList = [
        ['subjek_nama','Nama Subjek']
    ];

    public $field = [
        ['text','subjek_nama']
];

public $fieldName = [
        'Nama Subjek'
    ];

public $fieldOption = [
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new SubjekModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('subjek.*')
        ->where('subjek.deleted_at',NULL);

        $datatable = new Datatable();

        return $datatable->generate($builder, 'subjek.subjek_id',[
            'subjek.subjek_nama'
        ]);
    }

}
