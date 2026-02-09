<?php

namespace App\Models;

use CodeIgniter\Model;

class UserDivisionModel extends Model
{
    protected $table      = 'user_divisions';
    protected $primaryKey= 'id';
    protected $returnType= 'array';

    protected $allowedFields = ['user_id','division_id'];

    protected $useTimestamps  = true;
    protected $useSoftDeletes = false;

    public function getUserDivisions($userId)
        {
            return $this->select('divisions.*')
                ->join('divisions', 'divisions.id = user_divisions.division_id')
                ->where('user_divisions.user_id', $userId)
                ->findAll();
        }

}
