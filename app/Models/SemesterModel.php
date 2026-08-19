<?php
namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends Model
{
    protected $table = 'semesters';
    protected $allowedFields = ['academic_year_id', 'name', 'number'];

    public function getByAcademicYear($academicYearId)
    {
        return $this->where('academic_year_id', $academicYearId)
            ->orderBy('number', 'ASC')
            ->findAll();
    }
}