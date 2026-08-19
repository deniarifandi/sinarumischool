<?php

namespace App\Models;

use CodeIgniter\Model;

class GradebookScoreModel extends Model
{
    protected $table = 'gradebook_scores';
    protected $allowedFields = [
        'gradebook_id', 'student_id', 'ct1', 'ct1_remedial',
        'ct2', 'ct2_remedial', 'individual_project', 'group_project'
    ];

    public function upsertBatch(array $data)
    {
        // builder()->upsertBatch pakai ON DUPLICATE KEY UPDATE
        // WAJIB ada UNIQUE KEY(gradebook_id, student_id) di tabel
        return $this->builder()->upsertBatch($data);
    }
}