<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TipeaktifitasModel;
use App\Libraries\datatable;
use Config\Database;

class Tipeaktifitas extends MyResourceController
{

    public $table = "Tipeaktifitas";
    public $title = "Activity Type";
    public $primaryKey = "tipeaktifitas_id";
    public $fieldList = [
        ['tipeaktifitas_nama','Activity Name']
        // ['Aktifitas_password','Password']
    ];

    public $selectList= [
            'Tipeaktifitas.*'
    ];

    public $toSearch = 
    [
        'Tipeaktifitas.*',
    ];


    public $field = [
        ['text','tipeaktifitas_nama'],
];

public $fieldName = [
        'Activity Type'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new TipeaktifitasModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

}
