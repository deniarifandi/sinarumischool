<?php

namespace App\Controllers;

// use CodeIgniter\RESTful\ResourceController;
use App\Models\ModulModel;
use App\Libraries\datatable;
use Config\Database;

class modul extends MyResourceController
{

    public $table = "modul";
    public $title = "Modul";
    public $primaryKey = "modul_id";
    public $fieldList = [
        ['guru_id','Guru ID'], 
    ];

    public $field = [
        ['select','guru_id'], 
        ['select','kelompok_id'],
        ['radio','petakonsep_id'],
        ['select','subjek_1']
    ];

    public $selectList= [
            'guru.*',
        ];

    public $toSearch = 
    [
        'guru.guru_nama'
    ];

     public $where = [
      
    ];


    public $fieldName = [
            'Guru', 
            'Kelompok',
            'Peta Konsep',
            'Nilai Agama Moral dan Budi Pekerti 1:'
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
        $this->fieldOption[0] = $this->getdata('guru'); 
        $this->fieldOption[1] = $this->getdata('kelompok'); 
        $this->fieldOption[2] = $this->getdata('petakonsep'); 
        $this->fieldOption[3] = $this->getdata('tujuan'); 
        $this->model = new ModulModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    // public function index(){
    //     return view('ppbp/modul_list', $this->dataToShow);
    // }

    // public function new()
    // {
    //     return view('ppbp/modul_add', $this->dataToShow);
    // }

    // public function edit($id = null)
    // {
    //     $row = $this->model->find($id);
    //     if (!$row) {
    //         throw new \CodeIgniter\Exceptions\PageNotFoundException("Data not found: $id");
    //     }

    //     $data = $this->prepareDataToShow();
    //     $data['data'] = $row;
    //     return view('ppbp/modul_edit_ppbp', $data);
    // }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('modul.*')
        ->where('modul.deleted_at',NULL);;

        $datatable = new Datatable();

        return $datatable->generate($builder, 'modul.modul_id',[
            'modul.modul_id'
        ]);
    }

    public function getdata($table){
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        $builder->select('*');
        $builder->where('deleted_at', null);
        $query = $builder->get();
        $result = $query->getResultArray();
        $indexedOnly = array_map('array_values', $result);

        // print_r($indexedOnly);
    
        return $indexedOnly;
    }


}
