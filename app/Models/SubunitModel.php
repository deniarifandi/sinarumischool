<?php

namespace App\Models;

use CodeIgniter\Model;

class SubunitModel extends Model
{
    protected $table = 'subunits';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id','unit_id', 'subunit_name', 'deskripsi']; // sesuaikan struktur tabel

    // public function getSubunitBySubject($subjectId)
    // {
    //     return $this->join('subjects', 'subjects.id = units.subject_id')
    //         ->groupStart()
    //             ->where('units.subject_id', $subjectId)
    //             ->orWhere('subjects.subject_name', 'All Subject')
    //         ->groupEnd()
    //         ->where('units.deleted_at', null)
    //         ->findAll();
    // }

     public function getByUnit($unitId)
    {
        return $this->where('unit_id', $unitId)
                    ->orderBy('id')
                    ->findAll();
    }
}