<?php

namespace App\Models;

use CodeIgniter\Model;

class SlaModel extends Model
{
    protected $table            = 'slas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'student_id',
        'arrivaltime',
        'problem',
        'reason',
        'reduction',
        'teacher_id'
    ];

}