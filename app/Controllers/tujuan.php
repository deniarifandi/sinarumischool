<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TujuanModel;
use App\Libraries\datatable;
use Config\Database;

class tujuan extends MyResourceController
{

    public $table = "tujuan";
    public $title = "Tujuan";
    public $primaryKey = "tujuan_id";
    public $fieldList = [
        ['judul','Kelompok'], 
        ['subjek_nama','Subjek'],
        ['isi','Tujuan']
    ];

    public $field = [
        ['select','kelompok_id'], 
        ['select','subjek_id'],
        ['text','isi'],
];

public $fieldName = [
        'Nama Kelompok', 
        'Subjek',
        'Tujuan'
    ];

public $fieldOption = [
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption'], 
    ['noOption'],
    ['noOption'],
    ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->fieldOption[0] = $this->getdata('kelompok'); 
        $this->fieldOption[1] = $this->getdata('subjek'); 
        $this->model = new TujuanModel();
        $this->dataToShow = $this->prepareDataToShow();
       
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('tujuan.*, kelompok.*, subjek.*')
        ->join('kelompok','kelompok.kelompok_id = tujuan.kelompok_id')
        ->join('subjek','subjek.subjek_id = tujuan.subjek_id')
        ->where('tujuan.deleted_at', null);

        $datatable = new Datatable();

        return $datatable->generate($builder, 'tujuan.tujuan_id',[
            'tujuan.kelompok_id',
            'tujuan.subjek_id',
            'tujuan.isi'
        ]);
    }

     

}
