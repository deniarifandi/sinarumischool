<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\SubtopikModel;
use App\Libraries\datatable;
use Config\Database;

class subtopik extends MyResourceController
{

    public $table = "subtopik";
    public $title = "Subtopik";
    public $primaryKey = "subtopik_id";
    public $fieldList = [
        ['topik_isi','Topik'], 
        ['subtopik_isi','Sub-Topik']
        
    ];

    public $field = [
        ['select','topik_id'],
        ['text','subtopik_isi'],
];

public $fieldName = [
        'Topik', 
        'Sub-Topik'
    ];

public $fieldOption = [
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->fieldOption[0] = $this->getdata('topik');
        $this->model = new SubtopikModel();
        $this->dataToShow = $this->prepareDataToShow();
       
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('topik.*, subtopik.*')
        ->join('topik','topik.topik_id = subtopik.subtopik_id')
        ->where('subtopik.deleted_at', null);

        $datatable = new Datatable();

        return $datatable->generate($builder, 'subtopik.subtopik_id',[
            'subtopik.subtopik_isi',
            'topik.topik_isi'
        ]);
    }

     

}
