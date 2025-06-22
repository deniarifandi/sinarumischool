<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\SubunitModel;
use App\Libraries\datatable;
use Config\Database;

class Subunit extends MyResourceController
{

    public $table = "Subunit";
    public $title = "Sub-Chapter";
    public $primaryKey = "subunit_id";
    public $fieldList = [
        ['subjek_nama','Subject'],
        ['unit_nama','Chapter'],
        ['subunit_nama','Sub-Chapter']
        // ['Unit_password','Password']
    ];

    public $selectList= [
            'Unit.*',
            'Subjek.*',
            'Subunit.*'
    ];

    public $joinTable = [
        ['Unit','Unit.unit_id = Subunit.unit_id','left'],
        ['Subjek', 'Unit.subjek_id = Subjek.subjek_id','left']
        
    ];

    public $toSearch = 
    [
        'Unit.*',
    ];


    public $field = [
        ['select','unit_id'],
        ['text','subunit_nama']
];

public $fieldName = [
        'Chapter',
        'Sub-Chapter'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new SubunitModel();
        $this->fieldOption[0] = $this->getdata('Unit'); 
        $this->dataToShow = $this->prepareDataToShow();
    }

}
