<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonplanAssessmentModel extends Model
{
    protected $table            = 'lessonplan_assessments';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';

    protected $allowedFields = [
        'lessonplan_id',
        'student_id',
        'score',
        'notes',
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'lessonplan_id' => 'required|integer',
        'student_id'   => 'required|integer',
        'score'        => 'permit_empty|integer|in_list[1,2,3,4]',
    ];
}