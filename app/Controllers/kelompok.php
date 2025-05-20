<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\KelompokModel;
use App\Libraries\datatable;
use Config\Database;

class kelompok extends MyResourceController
{

    public $table = "kelompok";
    public $title = "Kelompok";
    public $primaryKey = "kelompok_id";
    public $fieldList = [
        ['judul','Nama Kelas'], 
        ['deskripsi','Kelompok Usia']
    ];

    public $field = [
        ['text','judul'], 
        ['text','deskripsi'],
];

public $fieldName = [
        'Nama Kelas', 
        'Kelompok Usia'
    ];

public $fieldOption = [
  ['noOption'], 
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new KelompokModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table('kelompok')
            ->select('kelompok.*')
            ->where('kelompok.deleted_at', null);       

        // print_r($builder->get()->getResult());

        $datatable = new Datatable();

        return $datatable->generate($builder, 'kelompok.kelompok_id',[
            'kelompok.judul',
            'kelompok.deskripsi'
        ]);
    }

}
