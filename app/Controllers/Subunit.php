<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\SubunitModel;
use App\Libraries\datatable;
use Config\Database;

class Subunit extends MyResourceController
{

    public $table = "Subunit";
    public $title = "Sub-Chapter";
    public $primaryKey = "subunit_id";
    public $fieldList = [
        ['subjek_nama','Subject'],
        ['unit_nama','Chapter'],
        ['subunit_nama','Sub-Chapter'],
        ['subunit_jp','Hours']
        // ['Unit_password','Password']
    ];

    public $selectList= [
            'Unit.*',
            'Subjek.*',
            'Subunit.*'
    ];

    public $joinTable = [
        ['Unit','Unit.unit_id = Subunit.unit_id','left'],
        ['Subjek', 'Unit.subjek_id = Subjek.subjek_id','left']
        
    ];

    public $toSearch = 
    [
        'Unit.*',
    ];

     public $where = [
      
    ];


    public $field = [
        ['select','unit_id'],
        ['text','subunit_nama'],
        ['text','subunit_jp']
];

public $fieldName = [
        'Chapter',
        'Sub-Chapter',
        'Hours'
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new SubunitModel();
        $this->fieldOption[0] = $this->getdata('Unit'); 
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
   

        // print_r($builder->get()->getResult());
        // ecsho json_encode($builder->get()->getResult());
         return view('/report/subunit_print',['data' => $builder->get()->getResult()]);

    }

}
