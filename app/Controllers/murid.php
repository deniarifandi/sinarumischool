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
        ['judul','Kelas'],
        ['deskripsi','Description']
    ];

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

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('murid.* , kelompok.*')
        ->join('kelompok','kelompok.kelompok_id = murid.kelompok_id')
        ->where('murid.deleted_at',NULL);;

        $datatable = new Datatable();

        return $datatable->generate($builder, 'murid.murid_id',[
            'murid.kelompok_id',
            'murid.murid_nama'
        ]);
    }

}
