<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = ['roles', 'description'];

    protected $validationRules = [
        'roles' => 'required|min_length[3]|is_unique[roles.roles,id,{id}]',
        'description' => 'permit_empty|string'
    ];
}