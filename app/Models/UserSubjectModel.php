<?php

// namespace App\Models;

// use CodeIgniter\Model;

// class UserSubjectModel extends Model
// {
//     protected $table      = 'user_subjects';
//     protected $primaryKey = 'id';

//     protected $returnType     = 'array';
//     protected $useTimestamps  = false;

//     protected $allowedFields = [
//         'user_id',
//         'subject_id',
//     ];

//     public function getUserSubjects($userId)
//     {
//         return $this->where('user_id', $userId)
//                     ->join('subjects','subjects.id = user_subjects.subject_id')
//                     ->join('divisions','divisions.id = subjects.division_id')
//                     // ->orderBy('subject_name', 'ASC')
//                     ->findAll();
//     }
// }

namespace App\Models;

use CodeIgniter\Model;

class UserSubjectModel extends Model
{
    protected $table      = 'user_subjects';
    protected $primaryKey = 'id';

    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id',
        'subject_id',
    ];

     public function getUserSubjects($userId)
    {
        return $this->where('user_id', $userId)
                    ->join('subjects','subjects.id = user_subjects.subject_id')
                    ->join('divisions','divisions.id = subjects.division_id')
                    // ->orderBy('subject_name', 'ASC')
                    ->findAll();
    }

    /**
     * Get subjects with their assigned users.
     */
    public function getSubjectsWithUsers($divisionId)
    {
        return $this->db->table('subjects s')
            ->select('
                s.id AS subject_id,
                s.subject_code,
                s.subject_name,
                s.description,
                s.division_id,
                d.division_name AS division_name,
                u.id AS user_id,
                u.name AS user_name,
                u.username
            ')
            ->join(
                'divisions d',
                'd.id = s.division_id',
                'left'
            )
            ->join(
                'user_subjects us',
                'us.subject_id = s.id',
                'left'
            )
            ->join(
                'users u',
                'u.id = us.user_id',
                'left'
            )
            ->where('s.division_id', $divisionId)
            ->orderBy('s.subject_name', 'ASC')
            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get users assigned to one subject.
     */
    public function getSubjectUsers($subjectId)
    {
        return $this->db->table('user_subjects us')
            ->select('
                u.id,
                u.name,
                u.username,
                u.guru_jabatan,
                u.nip,
                u.guru_role
            ')
            ->join(
                'users u',
                'u.id = us.user_id'
            )
            ->where('us.subject_id', $subjectId)
            ->orderBy('u.name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get IDs of users assigned to a subject.
     */
    public function getAssignedUserIds($subjectId)
    {
        $rows = $this->select('user_id')
            ->where('subject_id', $subjectId)
            ->findAll();

        return array_map(
            fn($row) => (int) $row['user_id'],
            $rows
        );
    }

    /**
     * Save users assigned to a subject.
     *
     * This replaces the complete user assignment
     * for that subject.
     */
    public function saveSubjectUsers($subjectId, array $userIds = [])
    {
        $this->db->transStart();

        // Remove existing assignments.
        $this->where('subject_id', $subjectId)->delete();

        // Insert new assignments.
        foreach ($userIds as $userId) {

            $userId = (int) $userId;

            if (!$userId) {
                continue;
            }

            $this->insert([
                'subject_id' => $subjectId,
                'user_id'    => $userId,
            ]);
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }
}