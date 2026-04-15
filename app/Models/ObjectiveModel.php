<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectiveModel extends Model
{
    protected $table      = 'objectives';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'outcome_id',
        'objective_name',
        'description'
    ];

    protected $returnType = 'array';

}