<?php
namespace App\Models;

use CodeIgniter\Model;

class AcademicYearModel extends Model
{
    protected $table = 'academic_years';
    protected $allowedFields = ['division_id', 'name', 'start_date', 'end_date', 'is_active'];

    public function getByDivision($divisionId)
    {
        return $this->where('division_id', $divisionId)
            ->orderBy('start_date', 'DESC')
            ->findAll();
    }

    public function getActiveByDivision($divisionId)
    {
        return $this->where('division_id', $divisionId)
            ->where('is_active', 1)
            ->first();
    }

    /**
     * Set satu academic year jadi aktif, otomatis nonaktifkan yang lain
     * TAPI hanya dalam divisi yang sama — divisi lain tidak terpengaruh.
     */
    public function setActive($id)
    {
        $ay = $this->find($id);
        if (!$ay) return false;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->where('division_id', $ay['division_id'])->set(['is_active' => 0])->update();
        $this->update($id, ['is_active' => 1]);

        $db->transComplete();
        return $db->transStatus();
    }
}