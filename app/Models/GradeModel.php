<?php

namespace App\Models;

use CodeIgniter\Model;

class GradeModel extends Model
{
    protected $table      = 'grades';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'division_id',
        'grade_name',
        'sort_order'
    ];

    protected $useTimestamps = true;
    protected $useSoftDeletes = true;

    public function byDivision($divisionId)
    {
        return $this->where('division_id', $divisionId)
                    ->orderBy('sort_order', 'ASC')
                    ->orderBy('grade_name', 'ASC')
                    ->findAll();
    }
}
