<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TujuanModel;
use App\Libraries\datatable;
use Config\Database;

class Tujuan extends MyResourceController
{

    public $table = "Tujuan";
    public $title = "Objective";
    public $primaryKey = "tujuan_id";
    public $fieldList = [
        ['tujuan_nama','Objective'],
    // ['tujuan_date','Date'],
        ['subunit_nama','Sub-Chapter'],
        ['unit_nama','Chapter']

    ];

    public $selectList= [
            'Tujuan.*',
            // 'Tingkat.tingkat_nama',
            'Unit.*',
            'Subunit.*'
        ];


    public $toSearch = 
    [
        // 'Tingkat.tingkat_nama',
        'Unit.unit_nama'

    ];

    public $joinTable = [
        // ['Tingkat', 'Tingkat.tingkat_id = Tujuan.tingkat_id','left'],
        ['Subunit','Subunit.subunit_id = Tujuan.subunit_id','left'],
        ['Unit','Unit.unit_id = Subunit.unit_id','left']
    ];



    public $field = [
        ['select','subunit_id'],
        // ['date','tujuan_date'],
        ['text','tujuan_nama'],

];

public $fieldName = [
        'Chapter',
        'Objective',
        // 'Date'
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
        $this->fieldOption[0] = $this->getdata('Subunit'); 
        $this->model = new TujuanModel();
        $this->dataToShow = $this->prepareDataToShow();
       
    }


     

}
