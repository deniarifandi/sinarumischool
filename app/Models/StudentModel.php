<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table          = 'students';
    protected $primaryKey     = 'id';

    protected $allowedFields  = [
        'division_id',
        'class_id',
        'name',
        'gender',
        'birthdate',
        'student_code',
        'address',
        'murid_agama'
    ];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    public function byDivision(int $divisionId): array
    {
        return $this->where('division_id', $divisionId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

   public function studentDetail($divisionId, $classId = null)
{
    $builder = $this->select('students.*, classes.class_name')
                    ->join('classes', 'classes.id = students.class_id', 'left')
                    ->where('students.division_id', $divisionId);

    if ($classId !== null) {
        $builder->where('students.class_id', $classId);
    }

        return $builder->groupBy('students.id')
                       ->orderBy('name', 'ASC')
                       ->findAll();
    }

    public function getByClass($class_id)
    {
        return $this->where('class_id', $class_id)
                    ->where('deleted_at', null)
                    ->findAll();
    }
}
