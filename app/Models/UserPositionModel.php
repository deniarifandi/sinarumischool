<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPositionModel extends Model
{
    protected $table            = 'user_position';
    protected $primaryKey       = 'gurujabatan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $deletedField     = 'deleted_at';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = ['guru_id', 'jabatan_id'];
}