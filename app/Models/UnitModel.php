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

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    protected $useSoftDeletes = true;

    public function getUnitBySubject($subjectId)
    {
        return $this->select('units.*')
        ->join('subjects', 'subjects.id = units.subject_id')
            ->groupStart()
                ->where('units.subject_id', $subjectId)
                ->orWhere('subjects.subject_name', 'All Subject')
            ->groupEnd()
            ->where('units.deleted_at', null)
            ->findAll();
    }

}