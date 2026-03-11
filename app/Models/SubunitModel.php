<?php

namespace App\Models;

use CodeIgniter\Model;

class SubunitModel extends Model
{
    protected $table = 'subunit';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['unit_id', 'name']; // sesuaikan struktur tabel
}