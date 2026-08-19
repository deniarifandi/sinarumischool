<?php

namespace App\Models;

use CodeIgniter\Model;

class GradebookModel extends Model
{
    protected $table = 'gradebooks';
    protected $allowedFields = [ 'term_id', 'class_id', 'subject_id', 'is_locked'];

    // public function firstOrCreate(array $criteria)
    // {
    //     $existing = $this->where($criteria)->first();
    //     if ($existing) {
    //         return $existing;
    //     }

    //     $id = $this->insert($criteria);
    //     return $this->find($id);
    // }

    public function firstOrCreate(array $criteria)
{
    $existing = $this->where($criteria)->first();
    if ($existing) {
        return $existing;
    }

    // Gradebook baru harus ikut status lock term-nya saat ini,
    // bukan selalu default unlocked.
    $termModel = new \App\Models\TermModel();
    $term = $termModel->find($criteria['term_id']);

    if (!$term) {
        // term_id tidak valid — jangan buat gradebook, biar controller yang handle.
        return null;
    }

    $criteria['is_locked']     = $term['is_locked'] ?? 0;
    $criteria['lock_override'] = 0; // baru dibuat, belum pernah di-override manual

    $id = $this->insert($criteria);
    return $this->find($id);
}

    public function overrideLock($gradebookId, bool $locked)
    {
        return $this->update($gradebookId, [
            'is_locked'     => $locked ? 1 : 0,
            'lock_override' => 1,
        ]);
    }

    public function resetOverride($gradebookId)
    {
        $gradebook = $this->find($gradebookId);
        if (!$gradebook) return false;

        $term = (new \App\Models\TermModel())->find($gradebook['term_id']);

        // Kembalikan mengikuti status term saat ini
        return $this->update($gradebookId, [
            'is_locked'     => $term['is_locked'] ?? 0,
            'lock_override' => 0,
        ]);
    }
}