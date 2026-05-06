<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonplanModel extends Model
{
    protected $table            = 'lessonplan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'class_id',
        'unit_id',
        'subunit_id',
        'semester',
        'bulan',
        'dpl',
        'agama1',
        'agama2',
        'jati1',
        'jati2',
        'dasar1',
        'dasar2',
        'iktp',
        'pedagogis',
        'kemitraan',
        'alatbahan',
        'sumber',
        'inti',
        'penutup',
        'sambut1','sambut2','sambut3','sambut4','sambut5',
        'pembukaan',
        'inti1','inti2','inti3','inti4','inti5','subject_id'

    ];

    protected $useTimestamps = false;

    // Get all
    public function getAll()
    {
        return $this->findAll();
    }

    // Get by ID
    public function getById($id)
    {
        return $this->find($id);
    }

    // Insert
    public function createData($data)
    {
        return $this->insert($data);
    }

    // Update
    public function updateData($id, $data)
    {
        return $this->update($id, $data);
    }

    // Delete
    public function deleteData($id)
    {
        return $this->delete($id);
    }
}