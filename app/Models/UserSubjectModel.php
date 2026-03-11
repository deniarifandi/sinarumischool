<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSubjectModel extends Model
{
    protected $table      = 'user_subjects';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useTimestamps  = false;

    protected $allowedFields = [
        'user_id',
        'subject_id',
    ];

    public function getUserSubjects($userId)
    {
        return $this->where('user_id', $userId)
                    ->join('subjects','subjects.id = user_subjects.subject_id')
                    ->join('divisions','divisions.id = subjects.division_id')
                    // ->orderBy('subject_name', 'ASC')
                    ->findAll();
    }
}