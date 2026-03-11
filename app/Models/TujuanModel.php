<?php

namespace App\Models;

use CodeIgniter\Model;

class TujuanModel extends Model
{
    protected $table      = 'tujuan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['grade_id','subject_id','nama'];

    public function getAgamaByGrade($gradeId)
    {
        return $this->where('grade_id', $gradeId)
                    ->where('subject_id', 1)
                    ->findAll();
    }
    public function getJatiByGrade($gradeId)
    {
        return $this->where('grade_id', $gradeId)
                    ->where('subject_id', 2)
                    ->findAll();
    }

    public function getLiterasiByGrade($gradeId)
    {
        return $this->where('grade_id', $gradeId)
                    ->where('subject_id', 3)
                    ->findAll();
    }
}