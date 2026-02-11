<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table          = 'subjects';
    protected $primaryKey     = 'id';

    protected $allowedFields  = [
        'division_id',
        'subject_code',
        'subject_name',
        'description',
    ];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = false;

    public function byDivision(int $divisionId): array
    {
        return $this->where('division_id', $divisionId)
                    ->orderBy('subject_name', 'ASC')
                    ->findAll();
    }
}
