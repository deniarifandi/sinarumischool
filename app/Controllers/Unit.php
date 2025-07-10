<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\UnitModel;
use App\Libraries\datatable;
use Config\Database;

class Unit extends MyResourceController
{

    public $table = "Unit";
    public $title = "Chapter";
    public $primaryKey = "unit_id";
    public $fieldList = [
        ['subjek_nama','Subject'],
        ['tingkat_nama','Grade'],
        ['unit_nama','Chapter'],
        ['unit_jp','Hours'],
        // ['Unit_password','Password']
    ];

    public $selectList= [
            'Unit.*',
            'Subjek.*',
            'Tingkat.*',
            'Subunit.subunit_nama',
            'Subunit.subunit_jp',
            'Tujuan.*'
    ];

    public $joinTable = [
        ['Subjek', 'Unit.subjek_id = Subjek.subjek_id','left'],
        ['Tingkat','Tingkat.tingkat_id = Unit.tingkat_id','left'],
        ['Subunit','Subunit.unit_id = Unit.unit_id','left'],
        ['Tujuan','Tujuan.subunit_id = subunit.subunit_id','left'],
        ['Aktifitas','Aktifitas.tujuan_id = Tujuan.tujuan_id','left']
        // ['Aktifitas','Aktifitas.tujuan_id']
    ];

    public $toSearch = 
    [
        // 'Unit.*',
    ];

    public $where = [
       
    ];



    public $field = [
        ['select','subjek_id'],
        ['select','tingkat_id'],
        ['text','unit_nama'],
        ['text','unit_jp']
];

public $fieldName = [
        'Subject',
        'Grade',
        'Chapter',
        'Hours'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption'],

];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new UnitModel();
        $this->fieldOption[0] = $this->getdata('Subjek'); 
        $this->fieldOption[1] = $this->getdata('Tingkat'); 
        $this->dataToShow = $this->prepareDataToShow();
    }

     public function print(){
        
       $builder = Database::connect()->table($this->table);
        $builder->select($this->table.'.*');

         $builder->select(implode(', ', $this->selectList));
        if (!empty($this->joinTable)) {
            foreach ($this->joinTable as $join) {
                    // $join[0] = join table name
                    // $join[1] = join condition
                // $builder->select($join[0] . '.*');
                $builder->join($join[0], $join[1],$join[2]);
            }
        }
        $builder->where($this->table.'.deleted_at',NULL);;

        foreach ($this->where as $key => $value) {
            $builder->where($key, $value);
        }
        $builder->orderBy('Unit.tingkat_id');
        $builder->orderBy('Subjek.subjek_id');
        $builder->orderBy('Unit.unit_id');
        $builder->orderBy('Subunit.subunit_id');


        // print_r($builder->get()->getResult());
        // ecsho json_encode($builder->get()->getResult());
         return view('/report/unit_print',['data' => $builder->get()->getResult()]);

    }

}
