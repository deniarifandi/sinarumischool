<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Score setiap student per objektif dalam sebuah gradebook.
 * UNIQUE (gradebook_id, objective_id, student_id).
 * Kolom objektif AUTOMATIS diambil dari objectives (term + subject),
 * jadi tidak ada tabel join kolom — score langsung key oleh objective_id.
 */
class GradebookObjectiveScoreModel extends Model
{
    protected $table      = 'gradebook_objective_scores';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'gradebook_id', 'objective_id', 'student_id', 'score'
    ];

    protected $returnType = 'array';

    /**
     * Semua score untuk satu gradebook, keyed:
     * [ objective_id ][ student_id ] => score
     */
    public function getByGradebook(int $gradebookId)
    {
        $rows = $this
            ->where('gradebook_id', $gradebookId)
            ->findAll();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['objective_id']][$row['student_id']] = $row['score'];
        }
        return $map;
    }

    public function upsertBatch(array $data)
    {
        // ON DUPLICATE KEY UPDATE — wajib UNIQUE(gradebook_id, objective_id, student_id)
        return $this->builder()->upsertBatch($data);
    }
}