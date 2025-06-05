<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\KelompokModel;
use App\Libraries\datatable;
use Config\Database;

class kelompok extends MyResourceController
{

    public $table = "kelompok";
    public $title = "Class";
    public $primaryKey = "kelompok_id";
    
    public $fieldList = [
         ['judul', 'murid.nama'],
         ['judul', 'kelompok.judul']       
    ];

   public $field = [
        ['text','judul'], 
        ['text','description'],
    ];

    public $fieldName = [
        'Nama Kelas', 
        'Description'
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
        $builder = Database::connect()->table($this->table)
            ->select($this->table . '.*')
            ->where($this->table . '.deleted_at', null);

        $datatable = new Datatable();

       $fieldColumns = [];
        foreach ($this->field as $f) {
            $fieldColumns[] = $this->table . '.' . $f[1]; // use only the field name, not type
        }

        return $datatable->generate($builder, $this->table . '.' . $this->primaryKey, $fieldColumns);
    }

}
