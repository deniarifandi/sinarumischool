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

    public function getGradeHistory(int $studentId, string $par = 'asc'): array
{
    $rows = $this->db->table('gradebook_scores gs')
        ->select('
            gs.*,
            gb.term_id, gb.class_id, gb.subject_id,
            t.name as term_name, t.start_date,
            s.name as semester_name,
            ay.name as academic_year_name,
            sub.subject_name,
            c.class_name
        ')
        ->join('gradebooks gb', 'gb.id = gs.gradebook_id')
        ->join('terms t', 't.id = gb.term_id')
        ->join('semesters s', 's.id = t.semester_id')
        ->join('academic_years ay', 'ay.id = s.academic_year_id')
        ->join('subjects sub', 'sub.id = gb.subject_id')
        ->join('classes c', 'c.id = gb.class_id')
        ->where('gs.student_id', $studentId)
        ->orderBy('t.start_date', $par)
        ->get()
        ->getResultArray();

    return $rows;
}
}