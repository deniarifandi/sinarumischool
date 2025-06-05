<?php

namespace App\Controllers;

use App\Models\MuridModel;

class murid extends MyResourceController
{

    public $table = "murid";
    public $title = "Murid";
    public $primaryKey = "murid_id";

    //List Parameter
    public $fieldList = [
        ['murid_nama','Nama Murid'], 
        ['kelompok_nama','Kelas'],
        ['guru_nama','Class Teacher'],
        ['deskripsi','Description']
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
        'Nama Murid', 
        'Kelompok'
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
