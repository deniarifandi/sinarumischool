<?php

namespace App\Models;

use CodeIgniter\Model;

class AktifitasModel extends Model
{
    // isi PostModel Class    
    protected $table      = 'Aktifitas';
    protected $primaryKey = 'aktifitas_id';
    
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