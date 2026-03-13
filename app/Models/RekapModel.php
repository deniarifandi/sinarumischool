<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapModel extends Model
{
    protected $table      = 'setting_rekap_501';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'division_id',
        'user_group',
        'group_sort',
        'user_role',
        'role_sort',
        'user_id',
        'nullified'
    ];

    protected $useTimestamps = false;
}