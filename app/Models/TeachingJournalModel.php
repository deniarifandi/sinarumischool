<?php

namespace App\Models;

use CodeIgniter\Model;

class TeachingJournalModel extends Model
{
    protected $table         = 'teaching_journals';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $allowedFields = [
        'date',
        'teacher_id',
        'subject_id',
        'class_id',
        'unit_id',
        'subunit_id',
        'periods',
        'start_time',
        'end_time',
        'topic',
        'activities',
        'notes',
    ];

    /**
     * Get a single journal with joined labels (teacher, subject, class, unit, subunit).
     */
    public function getDetail(int $id)
    {
        return $this->select('
                teaching_journals.*,
                users.name        AS teacher_name,
                users.username    AS teacher_username,
                subjects.subject_name,
                classes.class_name,
                grades.grade_name,
                grades.id         AS grade_id,
                units.name        AS unit_name,
                subunits.subunit_name
            ')
            ->join('users',     'users.id = teaching_journals.teacher_id', 'left')
            ->join('subjects',  'subjects.id = teaching_journals.subject_id', 'left')
            ->join('classes',   'classes.id = teaching_journals.class_id', 'left')
            ->join('grades',    'grades.id = classes.grade', 'left')
            ->join('units',     'units.id = teaching_journals.unit_id', 'left')
            ->join('subunits',  'subunits.id = teaching_journals.subunit_id', 'left')
            ->where('teaching_journals.id', $id)
            ->where('teaching_journals.deleted_at', null)
            ->first();
    }

    /**
     * Build a filtered listing.
     *
     * Filters:
     *   - teacher_id  (default: current session user)
     *   - subject_id  (optional)
     *   - class_id    (optional)
     *   - date_from   (optional, Y-m-d)
     *   - date_to     (optional, Y-m-d)
     */
    public function getList(array $filters = [])
    {
        $builder = $this->select('
                teaching_journals.*,
                users.name         AS teacher_name,
                subjects.subject_name,
                classes.class_name,
                grades.grade_name,
                units.name         AS unit_name,
                subunits.subunit_name
            ')
            ->join('users',     'users.id = teaching_journals.teacher_id', 'left')
            ->join('subjects',  'subjects.id = teaching_journals.subject_id', 'left')
            ->join('classes',   'classes.id = teaching_journals.class_id', 'left')
            ->join('grades',    'grades.id = classes.grade', 'left')
            ->join('units',     'units.id = teaching_journals.unit_id', 'left')
            ->join('subunits',  'subunits.id = teaching_journals.subunit_id', 'left')
            ->where('teaching_journals.deleted_at', null)
            ->orderBy('teaching_journals.date', 'DESC')
            ->orderBy('teaching_journals.id', 'DESC');

        if (!empty($filters['teacher_id'])) {
            $builder->where('teaching_journals.teacher_id', (int) $filters['teacher_id']);
        }

        if (!empty($filters['subject_id'])) {
            $builder->where('teaching_journals.subject_id', (int) $filters['subject_id']);
        }

        if (!empty($filters['class_id'])) {
            $builder->where('teaching_journals.class_id', (int) $filters['class_id']);
        }

        if (!empty($filters['date_from'])) {
            $builder->where('teaching_journals.date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $builder->where('teaching_journals.date <=', $filters['date_to']);
        }

        return $builder->findAll();
    }
}
