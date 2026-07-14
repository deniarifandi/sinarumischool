<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table          = 'students';
    protected $primaryKey     = 'id';

    protected $allowedFields = [
    'division_id',
    'class_id',
    'name',
    'student_code',
    'gender',
    'birthdate',
    'address',
    'murid_agama',

    'nickname',
    'birth_place',
    'nationality',
    'child_order',
    'family_status',
    'language',

    'father_name',
    'father_education',
    'father_occupation',

    'mother_name',
    'mother_education',
    'mother_occupation',

    'guardian_name',
    'guardian_relationship',
    'parent_address',
    'parent_phone',

    'blood_type',
    'weight',
    'height',

    'medical_history',
    'immunization_history',
    'speech_development',
    'physical_condition',

    'admission_date',
    'admission_age',
    'group_name',

    'exit_date',
    'exit_reason',
    'next_school',

    'achievements',
    'development_notes',
    'remarks',
    'photo',
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
