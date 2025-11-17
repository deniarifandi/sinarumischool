<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\JabatanModel;
use App\Libraries\datatable;
use Config\Database;

class Jabatan extends MyResourceController
{

    public $table = "Jabatan";
    public $title = "Jabatan";
    public $primaryKey = "jabatan_id";
    public $fieldList = [
        ['jabatan_nama','Jabatanon Name']
    ];

    public $selectList= [
            'Jabatan.jabatan_id',
            'Jabatan.jabatan_nama'
    ];

    public $joinTable = [
    ];

    public $toSearch = 
    [
        // 'Jabatan.*',
    ];

    public $where = [
       
    ];



    public $field = [
        ['text','jabatan_nama']
];

public $fieldName = [
        'jabatan Name'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption'],

];

// public $orderBy = ['Subjek.subjek_nama' => 'ASC'];
public $groupBy = 'Jabatan.jabatan_nama';

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new JabatanModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    

}
