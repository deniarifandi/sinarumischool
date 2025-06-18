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
            'Tingkat.*'
        ];

    public $toSearch = 
    [
        'Kelompok.kelompok_nama',
        'Guru.guru_nama'
    ];

    public $joinTable = [
        ['Guru', 'Guru.guru_id = Kelompok.guru_id','left'],
        ['Tingkat','Kelompok.tingkat_id = Tingkat.tingkat_id','left']
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
        $this->fieldOption[3] = $this->getdata('Tingkat'); 
        $this->model = new KelompokModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function print(){
        
        $db = \Config\Database::connect();
        $builder = $db->table('Kelompok');
        $builder->select('*');
        $builder->join('Guru','Kelompok.guru_id = Guru.guru_id');
        $builder->join('Tingkat','Tingkat.tingkat_id = Kelompok.tingkat_id');
        $builder->where('Kelompok.deleted_at', null);
        $query = $builder->get();

        return view('/report/kelompok_print',['data' => $query->getResult()]);

    }

}
