<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\AktifitasModel;
use App\Libraries\datatable;
use Config\Database;

class Aktifitas extends MyResourceController
{

    public $table = "Aktifitas";
    public $title = "Daily Activity";
    public $primaryKey = "aktifitas_id";
    public $fieldList = [
        
        ['subjek_nama','Subject'],
        ['unit_nama','Chapter'],
        ['subunit_nama','Sub-Chapter'],
        ['tipeaktifitas_nama','Activity Type'],
        ['aktifitas_date','Date'],
        // ['Aktifitas_password','Password']
    ];

    public $selectList= [
            'Aktifitas.*',
            'Tujuan.*',
            'Tipeaktifitas.*',
            'Subjek.*',
            'Unit.*',
            'Subunit.*'
    ];

    public $joinTable = [
        ['Tujuan', 'Tujuan.tujuan_id = Aktifitas.tujuan_id','left'],
        ['Subunit','Subunit.subunit_id = Tujuan.subunit_id','left'],
        ['Unit','Unit.unit_id = Subunit.unit_id','left'],
        ['Subjek','Subjek.subjek_id = Unit.subjek_id','left'],
        ['Tipeaktifitas','Tipeaktifitas.tipeaktifitas_id = Aktifitas.tipeaktifitas_id','left']

    ];

    public $toSearch = 
    [
        'Aktifitas.*',
    ];


    public $field = [
        ['select','tujuan_id'],
        ['select','tipeaktifitas_id'],
        ['text','aktifitas_nama'],
        ['date','aktifitas_date']
];

public $fieldName = [
        'Lesson Objective',
        'Activity Type',
        'Activity Name',
        'Activity Date'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new AktifitasModel();
        $this->fieldOption[0] = $this->getdata('Tujuan'); 
        $this->fieldOption[1] = $this->getdata('Tipeaktifitas'); 
        $this->dataToShow = $this->prepareDataToShow();
    }

}
