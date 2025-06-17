<?php

namespace App\Controllers;

use App\Models\MuridModel;

class Murid extends MyResourceController
{

    public $table = "Murid";
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
            'Murid.*',
            'Kelompok.*',
            'Guru.*'
        ];

    public $toSearch = 
    [
        'Kelompok.kelompok_nama',
        'Murid.murid_nama',
        'Guru.guru_nama'
    ];

    public $joinTable = [
        ['Kelompok', 'Murid.kelompok_id = Kelompok.kelompok_id','left'],
        ['Guru', 'Guru.guru_id = Kelompok.guru_id','left']
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
    $this->fieldOption[1] = $this->getdata('Kelompok'); 
    $this->model = new MuridModel();
    $this->dataToShow = $this->prepareDataToShow();
}



}
