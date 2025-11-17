<?php

namespace App\Controllers;


use App\Models\StudentModel;
use App\Libraries\datatable;
use Config\Database;

class Students extends MyResourceController
{

    public $table = "students";
    public $title = "Students";
    public $primaryKey = "student_id";

    //table
    public $fieldListName = [
        'student_name', 
        'student_email', 
        'student_username',
        'student_password',
        'student_gender'
    ];
    public $fieldList = [
        'student_name', 
        'student_email', 
        'student_username',
        'student_password',
        'student_gender'
    ];

    //create
    public $field = [
        'student_name', 
        'student_email', 
        'student_username',
        'student_password',
        'student_gender'
    ];
    public $fieldType = [
        'text',
        'email',
        'text',
        'password',
        'drop'
    ];
    public $fieldName = [
        'Student\'s Name', 
        'Student\'s email', 
        'Student\'s Username',
        'Student\'s Password',
        'Student\'s Gender'
    ];
    public $fieldOption = [
        ["noOption"],
        ["noOption"],
        ["noOption"],
        ["noOption"],
        ["girl","boy"]
    ];

    public function __construct()
    {
        $this->model = new StudentModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('students.*, classes.class_name')
        ->join('classes', 'classes.class_id = students.student_id', 'left');

        $datatable = new Datatable();

        return $datatable->generate($builder, 'students.student_id', [
            'students.student_name',
            'students.student_email',
            'classes.class_name'
        ]);
    }

}
