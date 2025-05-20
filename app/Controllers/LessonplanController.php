<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\AssignmentModel;
use App\Libraries\datatable;
use Config\Database;

class Assignments extends MyResourceController
{

    public $table = "assignments";
    public $title = "Assignments";
    public $primaryKey = "assignment_id";
    public $fieldList = [
        ['assignment_name','Title'], 
        ['description','Description'],
        ['subject_name','Subject'],
        ['grade', 'Grade'], 
        ['class_a','A'],
        ['class_b','B'],
        ['class_c','C'],
        ['class_d','D'],
        ['due_date','Due Date'],
        ['file_url','File']
    ];

    public $field = [
        ['text','assignment_name'], 
        ['text','description'],
        ['select','subject_id'],
        ['select','grade'], 
        ['select','class_a'],
        ['select','class_b'],
        ['select','class_c'],
        ['select','class_d'],
        ['date','due_date'],
        ['file','file_url']
];

public $fieldName = [
        'Assignment\'s Title', 
        'Description',
        'Subject',
        'Grade\'s', 
        'Class: A',
        'Class: B',
        'Class: C',
        'Class: D',
        'Due Date',
        'File url'];

public $fieldOption = [
  ['noOption'], 
  ['noOption'], 
  [['0','ESL'],['1','MATH'],['2','SOCIOSCIENCE']], 
  [['1','1'], ['2','2'], ['3','3'], ['4','4'], ['5','5'], ['6','6']], 
  [['0','Not Included'],['1','Included']],
  [['0','Not Included'],['1','Included']],
  [['0','Not Included'],['1','Included']],
  [['0','Not Included'],['1','Included']],
  ['noOption'], 
  ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new AssignmentModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('assignments.*, subjects.*')
        ->join('subjects','assignments.subject_id = subjects.subject_id','left');

        $datatable = new Datatable();

        return $datatable->generate($builder, 'assignments.assignment_id',[
            'assignments.assignment_name',
            'assignments.file_url',
            'subjects.subject_name'
        ]);
    }

}
