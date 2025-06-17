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
        ['subjek_nama','Subject'],
        ['tujuan_nama','Objective'],
        ['tingkat_nama','Grade']

    ];

    public $selectList= [
            'Tujuan.*',
            'Subjek.*',
            'Tingkat.tingkat_nama'
        ];


    public $toSearch = 
    [
        'Tingkat.tingkat_nama'
    ];

    public $joinTable = [
        ['Tingkat', 'Tingkat.tingkat_id = Tujuan.tingkat_id','left'],
        ['Subjek', 'Subjek.subjek_id = Tujuan.subjek_id','left'],
    ];



    public $field = [
        ['select','subjek_id'],
        ['select','tingkat_id'], 
        ['text','tujuan_nama'],
];

public $fieldName = [
        'Subject',
        'Grade', 
        'Objective'
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
        $this->fieldOption[0] = $this->getdata('Subjek'); 
        $this->fieldOption[1] = $this->getdata('Tingkat'); 
        $this->model = new TujuanModel();
        $this->dataToShow = $this->prepareDataToShow();
       
    }


     

}
