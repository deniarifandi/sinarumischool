<?php

namespace App\Models;

use CodeIgniter\Model;

class TermModel extends Model
{
    protected $table = 'terms';
    protected $allowedFields = ['is_locked','semester_id','name','number','start_date','end_date','is_locked'];

public function getBySemester($semesterId)
{
    return $this->where('semester_id', $semesterId)
        ->orderBy('number', 'ASC')
        ->findAll();
}

    public function getActiveTerm($divisionId)
{
    return $this->select('terms.*, semesters.academic_year_id')
        ->join('semesters', 'semesters.id = terms.semester_id')
        ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
        ->where('academic_years.is_active', 1)
        ->where('academic_years.division_id', $divisionId)
        ->where('terms.start_date <=', date('Y-m-d'))
        ->where('terms.end_date >=', date('Y-m-d'))
        ->first();
}

    public function lockTerm($termId)
{
    $db = \Config\Database::connect();
    $db->transStart();

    $this->update($termId, ['is_locked' => 1]);

    $db->table('gradebooks')
        ->where('term_id', $termId)
        ->where('lock_override', 0) // jangan sentuh yang sudah di-override
        ->update(['is_locked' => 1]);

    $db->transComplete();
    return $db->transStatus();
}

public function unlockTerm($termId)
{
    $db = \Config\Database::connect();
    $db->transStart();

    $this->update($termId, ['is_locked' => 0]);

    $db->table('gradebooks')
        ->where('term_id', $termId)
        ->where('lock_override', 0)
        ->update(['is_locked' => 0]);

    $db->transComplete();
    return $db->transStatus();
}
}