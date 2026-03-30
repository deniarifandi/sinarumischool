<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassModel extends Model
{
    protected $table      = 'classes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'division_id',
        'grade',
        'class_name',
        'description',
        'classteacher_id'
    ];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    public function getClassDetail($class_id){
        return $this->select('classes.*, grades.grade_name, users.name')
                    ->where('classes.id',$class_id)
                    ->join('grades','grades.id = classes.grade','left')
                    ->join('users','users.id = classes.classteacher_id','left')
                    ->orderBy('grade', 'ASC')
                    ->orderBy('class_name', 'ASC')
                    ->findAll();
    }

    public function byDivision($divisionId)
    {
        return $this->select('classes.*, grades.grade_name, users.name')
                    ->where('classes.division_id', $divisionId)
                    ->join('grades','grades.id = classes.grade','left')
                    ->join('users','users.id = classes.classteacher_id','left')
                    ->orderBy('grade', 'ASC')
                    ->orderBy('class_name', 'ASC')
                    ->findAll();
    }

    public function byDivisionAndGrade($divisionId, $grade)
    {
        return $this->where('division_id', $divisionId)
                    ->where('grade', $grade)
                    ->orderBy('class_name', 'ASC')
                    ->findAll();
    }
}
