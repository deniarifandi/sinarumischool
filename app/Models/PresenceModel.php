<?php

// app/Models/PresenceModel.php
namespace App\Models;

use CodeIgniter\Model;

class PresenceModel extends Model
{
    protected $table      = 'presensidata';
    protected $primaryKey = 'presensidata_id';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'guru_id',
        'longitude',
        'latitude',
        'presensidata_tanggal',
        'status'
    ];

    public function presence_check(){

        $this->start = date('Y-m-d 00:00:00');
        $this->end   = date('Y-m-d 23:59:59');

        $result = $this->db->table('presensidata')
        ->where('guru_id', session('id'))
        ->where('created_at >=', $this->start)
        ->where('created_at <=', $this->end)
        ->get()
        ->getResultArray();

        return $result;
    }
}
