<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\TujuanModel;
use App\Libraries\datatable;
use Config\Database;

class tujuan extends MyResourceController
{

    public $table = "tujuan";
    public $title = "Objective";
    public $primaryKey = "tujuan_id";
    public $fieldList = [
        ['subjek_nama','Subject'],
        ['tujuan_nama','Objective']
    ];


    public $selectList= [
            'tujuan.*',
            'subjek.*'
        ];


    public $toSearch = 
    [
        'tingkat.tingkat_nama'
    ];

    public $joinTable = [
        ['tingkat', 'tingkat.tingkat_id = tujuan.tingkat_id','left'],
        ['subjek', 'subjek.subjek_id = tujuan.subjek_id','left'],
    ];



    public $field = [
        ['select','subjek_id'],
        ['select','tingkat_id'], 
        ['text','tujuan_nama'],
];

public $fieldName = [
        'Subject',
        'Grade', 
        'Objective'
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
        $this->fieldOption[0] = $this->getdata('subjek'); 
        $this->fieldOption[1] = $this->getdata('tingkat'); 
        $this->model = new TujuanModel();
        $this->dataToShow = $this->prepareDataToShow();
       
    }

    // public function data(){
    //     $builder = Database::connect()->table($this->table)
    //     ->select('tujuan.*, kelompok.*, subjek.*')
    //     ->join('kelompok','kelompok.kelompok_id = tujuan.kelompok_id')
    //     ->join('subjek','subjek.subjek_id = tujuan.subjek_id')
    //     ->where('tujuan.deleted_at', null);

    //     $datatable = new Datatable();

    //     return $datatable->generate($builder, 'tujuan.tujuan_id',[
    //         'tujuan.kelompok_id',
    //         'tujuan.subjek_id',
    //         'tujuan.isi'
    //     ]);
    // }

     

}
