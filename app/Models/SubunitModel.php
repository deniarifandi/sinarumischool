<?php

namespace App\Models;

use CodeIgniter\Model;

class SubunitModel extends Model
{
    protected $table = 'subunits';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id','unit_id', 'subunit_name', 'deskripsi']; // sesuaikan struktur tabel
}