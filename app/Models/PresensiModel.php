<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    // isi PostModel Class    
    protected $table      = 'Presensi';
    protected $primaryKey = 'guru_id';
    
    protected $protectFields = false;

    protected $useAutoIncrement = true;
    protected $useTimestamps = true; 
    protected $useSoftDeletes   = true;

    // protected $validationRules = [
    //     'student_name'     => 'required',
    //     'student_email'    => 'required|valid_email',
    //     'student_username' => 'required|alpha_numeric',
    // ];
 
}