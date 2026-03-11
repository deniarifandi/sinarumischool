<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table      = 'units';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'subject_id',
        'grade_id',
        'name'
    ];

    protected $returnType = 'array';

}