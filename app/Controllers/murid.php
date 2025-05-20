<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\MuridModel;
use App\Libraries\datatable;
use Config\Database;

class murid extends MyResourceController
{

    public $table = "murid";
    public $title = "Murid";
    public $primaryKey = "murid_id";
    public $fieldList = [
        ['murid_nama','Nama Murid'], 
        ['murid_kelompok','Kelompok']
    ];

    public $field = [
        ['text','murid_nama'], 
        ['select','murid_kelompok'],
];

public $fieldName = [
        'Nama Murid', 
        'Kelompok'
    ];

public $fieldOption = [
  ['noOption'], 
  [['0','BINTANG (3-4 TAHUN)'],['1','BULAN (4-5 TAHUN)'],['2','MATAHARI (5-6 TAHUN)']]
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new MuridModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('murid.*');        

        $datatable = new Datatable();

        return $datatable->generate($builder, 'murid.murid_id',[
            'murid.murid_kelompok',
            'murid.murid_nama'
        ]);
    }

}
