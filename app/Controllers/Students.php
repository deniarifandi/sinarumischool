<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StudentModel;
use App\Libraries\datatable;
use Config\Database;

class Students extends ResourceController
{

   // protected $modelName = 'App\Models\StudentModel';
    protected $format    = 'json';

    public function index()
    {   
      
        return view('/students/list');
      
    }    

     public function new()
    {
        // Show create user form
        return view('students/create');
    }

    public function data(){
        $builder = Database::connect()->table('students')
        ->select('students.*, classes.class_name')
        ->join('classes', 'classes.class_id = students.student_id', 'left');

        $datatable = new Datatable();

        return $datatable->generate($builder, 'students.student_id', [
            'students.student_name',
            'students.student_email',
            'classes.class_name'
        ]);
    }

    public function create()
    {
        $model = new StudentModel();

        $data = [
            'student_name' => $this->request->getPost('student_name'),
            'student_email' => $this->request->getPost('student_email'),
            'student_username' => $this->request->getPost('student_username'),
        ];

         try {
                if (!$model->insert($data)) {
                    return redirect()->back()->withInput()->with('errors', $model->errors());
                }

                return "Insert successful!";
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('errors', [$e->getMessage()]);
            }
    }

}
