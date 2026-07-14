<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentScoreModel extends Model
{
    protected $table = 'student_scores';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'grade_book_id',
        'student_id',
        'score',
        'remed_score',
        'remarks',
    ];

    protected $useTimestamps = true;
}