<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\UnitModel;
use App\Libraries\datatable;
use Config\Database;

class Unit extends MyResourceController
{

    public $table = "Unit";
    public $title = "Chapter";
    public $primaryKey = "unit_id";
    public $fieldList = [
        ['subjek_nama','Subject'],
        ['unit_nama','Chapter'],
        ['tingkat_nama','Grade']
        // ['Unit_password','Password']
    ];

    public $selectList= [
            'Unit.*',
            'Subjek.*',
            'Tingkat.*'
    ];

    public $joinTable = [
        ['Subjek', 'Unit.subjek_id = Subjek.subjek_id','left'],
        ['Tingkat','Tingkat.tingkat_id = Unit.tingkat_id','left']
    ];

    public $toSearch = 
    [
        'Unit.*',
    ];


    public $field = [
        ['select','subjek_id'],
        ['select','tingkat_id'],
        ['text','unit_nama']
];

public $fieldName = [
        'Subject',
        'Grade',
        'Chapter'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new UnitModel();
        $this->fieldOption[0] = $this->getdata('Subjek'); 
        $this->fieldOption[1] = $this->getdata('Tingkat'); 
        $this->dataToShow = $this->prepareDataToShow();
    }

}
