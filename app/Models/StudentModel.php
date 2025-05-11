<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    // isi PostModel Class    
    protected $table      = 'students';
    protected $primaryKey = 'student_id';
    protected $allowedFields = ['student_name'];

    protected $validationRules = [
        'student_name'        => 'required|min_length[10]',
    ];
}