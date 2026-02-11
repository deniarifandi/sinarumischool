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

    public function studentDetail($divisionId){
        return $this->select('students.*, classes.class_name')
                    ->where('students.division_id', $divisionId)
                    ->join('classes','classes.id = students.class_id','left')
                    ->groupBy('students.id')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
