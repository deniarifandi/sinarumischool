<?php

namespace App\Models;

use CodeIgniter\Model;

class SocioReportModel extends Model
{
    protected $table      = 'socioreport';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'class_id',
        'teacher_id',

        'student_id',
        'student_name',
        'class',
        'date',

        'trying','following',
        'sharing','kindness',
        'calm','composed',
        'focused','attempting',
        'cooperative','listening',
        'accepting','recovering',
        'improving','challenging',
        'contributing','supporting',
        'steady','resilient',
        'giving','reminders','upset',
        'reactive','not-sharing','rough',
        'distracted','avoiding','rejecting',
        'quitting','disruptive','interrupting',
        'resisting','avoidance','overreacting',
        'lingering','withdrawing','dominating'
    ];

    protected $useTimestamps = false;
}
