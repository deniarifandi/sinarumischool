<?php

namespace App\Controllers;

use App\Models\KelompokModel;

class kelompok extends MyResourceController
{

    public $table = "kelompok";
    public $title = "Class";
    public $primaryKey = "kelompok_id";
    
    public $fieldList = [
         ['kelompok_nama', 'Kelas'],
         ['guru_nama','Class Teacher'],
    ];

    public $toSearch = 
    [
        'kelompok.kelompok_nama',
        'guru.guru_nama'
    ];

    public $joinTable = [
        ['guru', 'guru.guru_id = kelompok.guru_id','left']
    ];

   public $field = [
        ['text','kelompok_nama'], 
        ['select','guru_id'],
        ['text','deskripsi'],
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
        $this->fieldOption[1] = $this->getdata('guru'); 
        $this->model = new KelompokModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

}
