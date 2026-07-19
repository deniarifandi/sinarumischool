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

    public function getUsersForDashboard()
    {
        return $this->select('users.id, users.name, users.username, users.role,
                               users.kkb, users.kkbnomor, users.kkbstart,
                               divisions.id as division_id, divisions.division_name')
                    ->join('user_divisions', 'user_divisions.user_id = users.id', 'left')
                    ->join('divisions', 'divisions.id = user_divisions.division_id', 'left')
                    ->where('users.deleted_at', null)
                    ->findAll();
    }
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

    public function getUsersDataByDivision($divisionId)
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
            ->where('divisions.id',$divisionId)
            ->where('users.role','teacher')
            ->orderBy('users.name')
            ->findAll();
    }

     public function getUserDetailData($user_id)
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

   public function getUserMainClass($user_id)
{
    return $this->select("
            classes.*,
            users.name,
            CASE
                WHEN classes.classteacher_id = {$user_id} THEN 'classteacher'
                WHEN classes.assclassteacher_id = {$user_id} THEN 'assistant'
            END AS teacher_role
        ")
        ->join(
            'classes',
            'users.id = classes.classteacher_id OR users.id = classes.assclassteacher_id'
        )
        ->groupStart()
            ->where('classes.classteacher_id', $user_id)
            ->orWhere('classes.assclassteacher_id', $user_id)
        ->groupEnd()
        ->where('classes.deleted_at', null)
        ->where('users.id', $user_id)
        ->findAll();
}
}
