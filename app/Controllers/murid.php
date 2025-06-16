<?php

namespace App\Controllers;

use App\Models\MuridModel;

class murid extends MyResourceController
{

    public $table = "murid";
    public $title = "Students";
    public $primaryKey = "murid_id";

    //List Parameter
    public $fieldList = [
        ['murid_nama','Student`s Name'], 
        ['kelompok_nama','Class'],
        ['guru_nama','Class Teacher'],
        ['deskripsi','Description']
    ];

      public $selectList= [
            'murid.*',
            'kelompok.*',
            'guru.*'
        ];

    public $toSearch = 
    [
        'kelompok.kelompok_nama',
        'murid.murid_nama',
        'guru.guru_nama'
    ];

    public $joinTable = [
        ['kelompok', 'murid.kelompok_id = kelompok.kelompok_id','left'],
        ['guru', 'guru.guru_id = kelompok.guru_id','left']
    ];

    //Insert & Update Parameter

    public $field = [
        ['text','murid_nama'], 
        ['select','kelompok_id'],
    ];

    public $fieldName = [
        'Student`s Name', 
        'Class'
    ];

    public $fieldOption = [
      ['noOption'], 
      ['noOption']
  ];

  public $dataToShow = [];

  public function __construct()
  {
    $this->fieldOption[1] = $this->getdata('kelompok'); 
    $this->model = new MuridModel();
    $this->dataToShow = $this->prepareDataToShow();
}



}
