<?php

namespace App\Models;

use CodeIgniter\Model;

class OutcomeModel extends Model
{
    protected $table      = 'outcomes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'subject_id',
        'outcome_name',
        'description'
    ];

    protected $returnType = 'array';

}