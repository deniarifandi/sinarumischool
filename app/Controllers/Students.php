<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StudentModel;
use App\Libraries\datatable;

class Students extends ResourceController
{

   // protected $modelName = 'App\Models\StudentModel';
    protected $format    = 'json';

    public function index()
    {   
      
        return view('/students/list');
      
    }    
    
    public function data(){
        $datatable = new Datatable();
        $model = new StudentModel();

        return $datatable->generate($model, ['student_name', 'student_email', 'student_password']);
    }

}
