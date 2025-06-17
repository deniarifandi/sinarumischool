<?php

namespace App\Controllers;

use App\Models\HariModel;

class hari extends MyResourceController
{

    public $table = "hari";
    public $title = "Daily Presence";
    public $primaryKey = "hari_id";
    
    public $fieldList = [
         ['ta_id', 'Kelas'],
         ['ta_id','Class Teacher'],
    ];

    public $toSearch = 
    [
        'kelompok.kelompok_nama',
        'guru.guru_nama'
    ];


   public $field = [
        ['text','kelompok_nama'], 
        ['select','guru_id'],
        ['text','deskripsi'],
    ];

    public $joinTable = [
        ['ta', 'ta.ta_id = hari.ta_id','left']
    ];

    public $fieldName = [
        'Nama Kelas', 
        'Class Teacher',
        'Description'
    ];

    public $fieldOption = [
        ['noOption'], 
        ['noOption'],
        ['noOption']

    ];

    public $dataToShow = [];

    public function __construct()
    {
        //$this->fieldOption[1] = $this->getdata(''); 
        $this->model = new HariModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

}
