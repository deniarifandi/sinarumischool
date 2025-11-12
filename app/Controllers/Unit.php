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

        ['tingkat_nama','Grade'],
        ['unit_nama','Chapter'],
        ['unit_jp','Hours'],
        // ['Unit_password','Password']
    ];

    public $selectList= [
            'Unit.unit_id',
            'Unit.unit_nama',
            'Unit.unit_jp',
            'Subjek.subjek_nama',
            'Tingkat.tingkat_nama',
            'Subunit.subunit_nama',
            'Subunit.subunit_jp',
            'Tujuan.tujuan_nama'
    ];

    public $joinTable = [
        ['Subjek', 'Unit.subjek_id = Subjek.subjek_id','left'],
        ['Tingkat','Tingkat.tingkat_id = Unit.tingkat_id','left'],
        ['Subunit','Subunit.unit_id = Unit.unit_id','left'],
        ['Tujuan','Tujuan.subunit_id = Subunit.subunit_id','left'],
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
       
        ['select','tingkat_id'],
        ['text','unit_nama'],
        ['text','unit_jp']
];

public $fieldName = [
      
        'Grade',
        'Chapter Name',
        'Hours'
        
    ];

public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption'],

];

// public $orderBy = ['Subjek.subjek_nama' => 'ASC'];
public $groupBy = 'Unit.unit_nama';

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new UnitModel();
        
        $this->fieldOption[0] = $this->getdata('Tingkat'); 
      
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function index()
    {   

        return view('/unit/list', $this->dataToShow);
    }    

     public function print(){
        
       $builder = Database::connect()->table($this->table);
        $builder->select($this->table.'.*');

         $builder->select(implode(', ', $this->selectList));
        if (!empty($this->joinTable)) {
            foreach ($this->joinTable as $join) {
                $builder->join($join[0], $join[1],$join[2]);
            }
        }
        // $builder->where($this->table.'.subjek_id',$_GET['subject']);
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

    public function data_unit($subjek_id){
        $builder = Database::connect()->table($this->table);
        // $builder->select($this->table.'.*');

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

        
        $builder->where($this->table.'.subjek_id',$subjek_id);
           

        if (!empty($this->orderBy)) {
            if (is_array($this->orderBy)) {
                // Handle multiple order conditions
                foreach ($this->orderBy as $key => $value) {
                    if (is_numeric($key) && is_array($value)) {
                        $builder->orderBy($value[0], $value[1]); // [['name', 'ASC']]
                    } else {
                        $builder->orderBy($key, $value); // ['name' => 'ASC']
                    }
                }
            } else {
                // Assume it's a string like "name ASC"
                [$column, $direction] = explode(' ', $this->orderBy);
                $builder->orderBy($column, $direction);
            }
        }

        if (!empty($this->groupBy)) {
            
                // string — can be one or more columns separated by commas
                $builder->groupBy($this->groupBy);
            
        }

        $datatable = new Datatable();

        return $datatable->generate($builder, $this->table.'.'.$this->primaryKey, $this->toSearch);
    }

     public function new()
    {
        
        return view('/unit/create', $this->dataToShow);
    }

}
