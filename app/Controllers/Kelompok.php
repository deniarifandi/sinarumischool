<?php

namespace App\Controllers;

use App\Models\KelompokModel;

class Kelompok extends MyResourceController
{

    public $table = "Kelompok";
    public $title = "Class";
    public $primaryKey = "kelompok_id";
    
    public $fieldList = [
         ['kelompok_nama', 'Class'],
         // ['guru_id','Guru'],
         ['tingkat_nama','Grade'],
         ['guru_nama','Class Teacher']
         
    ];


    public $selectList= [
            'Kelompok.*',
            'Guru.*',
            'tingkat.*'
        ];

    public $toSearch = 
    [
        'Kelompok.kelompok_nama',
        'Guru.guru_nama'
    ];

    public $joinTable = [
        ['guru', 'Guru.guru_id = Kelompok.guru_id','left'],
        ['tingkat','Kelompok.tingkat_id = tingkat.tingkat_id','left']
    ];

   public $field = [
        ['text','kelompok_nama'], 
        ['select','guru_id'],
        ['text','deskripsi'],
        ['select','tingkat_id']
    ];

    public $fieldName = [
        'Nama Kelas', 
        'Class Teacher',
        'Description',
        'Grade'
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
        $this->fieldOption[1] = $this->getdata('Guru'); 
        $this->fieldOption[3] = $this->getdata('tingkat'); 
        $this->model = new KelompokModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

}
