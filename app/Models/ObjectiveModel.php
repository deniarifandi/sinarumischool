<?php

namespace App\Models;

use CodeIgniter\Model;

class ObjectiveModel extends Model
{
    protected $table      = 'objectives';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'id',
        'outcome_id',
        'objective_name',
        'description'
    ];

    protected $returnType = 'array';

     public function getAgamaBySubject($subjectId)
    {
        return $this->join('outcomes','outcomes.id = objectives.outcome_id')
                    ->where('outcomes.subject_id', $subjectId)
                    ->where("outcome_name LIKE '%agama%'")
                    ->findAll();
    }
     public function getJatiBySubject($subjectId)
    {
        return $this->join('outcomes','outcomes.id = objectives.outcome_id')
                    ->where('outcomes.subject_id', $subjectId)
                    ->where("outcome_name LIKE '%jati%'")
                    ->findAll();
    }
     public function getLiterasiBySubject($subjectId)
    {
        return $this->join('outcomes','outcomes.id = objectives.outcome_id')
                    ->where('outcomes.subject_id', $subjectId)
                    ->where("outcome_name LIKE '%literasi%'")
                    ->findAll();
    }

}