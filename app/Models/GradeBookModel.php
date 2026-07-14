<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeBookModel extends Model
{
    protected $table = 'grade_books';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'academic_year_id',
        'term',
        'class_id',
        'subject_id',
        'assessment_type_id',
        'teacher_id',
        'status',
    ];

    protected $useTimestamps = true;
}