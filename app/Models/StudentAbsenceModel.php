<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAbsenceModel extends Model
{
    protected $table          = 'student_absence';
    protected $primaryKey     = 'absensi_id';

    protected $allowedFields  = [
        'tanggal',
        'murid_id',
        'status',
        'absensi_keterangan',
    ];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = true;

    public function byDate(string $date): array
    {
        return $this->where('tanggal', $date)
                    ->findAll();
    }

    public function byStudent(int $studentId): array
    {
        return $this->where('murid_id', $studentId)
                    ->orderBy('tanggal', 'DESC')
                    ->findAll();
    }
}
