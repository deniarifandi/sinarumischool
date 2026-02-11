<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

  protected $allowedFields = [
  'username','password','name','nip','nik','gender','placebirth','datebirth','religion','marital',
  'phone','bca','address',
  'bpjskesehatan','bpjsketenagakerjaan',
  'kkb','kkbstart','kkbnomor',
  'pasfoto','filektp','filekk','arsip','role'
];

    protected $useTimestamps  = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $deletedField   = 'deleted_at';

    protected $useSoftDeletes = true;

    public function getUsersData()
    {
        return $this->select('
                users.id,
                users.name,
                users.role,
                users.username,
                users.pasfoto,
                divisions.division_name,
                divisions.id as division_id
            ')
            ->join('user_divisions', 'user_divisions.user_id = users.id', 'left')
            ->join('divisions', 'divisions.id = user_divisions.division_id', 'left')
            ->orderBy('users.id')
            ->findAll();
    }

     public function getUsersDetailData($user_id)
    {
        return $this->select('
                users.id,
                users.name,
                users.role,
                users.username,
                users.pasfoto,
                divisions.division_name,
                divisions.id as division_id
            ')
            ->join('user_divisions', 'user_divisions.user_id = users.id', 'left')
            ->join('divisions', 'divisions.id = user_divisions.division_id', 'left')
            ->where('users.id',$user_id)
            ->orderBy('users.id')
            ->findAll();
    }
}
