<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TingkatModel;
use App\Libraries\datatable;
use Config\Database;

class Tingkat extends MyResourceController
{

    public $table = "Tingkat";
    public $title = "Grade";
    public $primaryKey = "tingkat_id";
    public $fieldList = [
        ['tingkat_nama','Grade']
        // ['tingkat_password','Password']
    ];


    public $selectList= [
            '$table.*',
        ];

    public $toSearch = 
    [
        'Tingkat.*'
    ];


    public $field = [
        ['text','tingkat_nama']
];

public $fieldName = [
        'Grade'
    ];

public $fieldOption = [
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new TingkatModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    // public function data(){
    //     $builder = Database::connect()->table('tingkat')
    //         ->select('tingkat.*')
    //         ->where('tingkat.deleted_at', null);       

    //     // print_r($builder->get()->getResult());

    //     $datatable = new Datatable();

    //     return $datatable->generate($builder, 'tingkat.tingkat_id',[
    //         'tingkat.tingkat_nama'
    //     ]);
    // }

}
