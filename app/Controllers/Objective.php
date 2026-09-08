<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\ObjectiveModel;
use App\Models\OutcomeModel;

class Objective extends BaseController
{
    protected $unitModel;
    protected $subjectModel;
    protected $gradeModel;
    protected $outcomeModel;
    protected $objectiveModel;

    public function __construct()
    {
        $this->unitModel      = new UnitModel();
        $this->subjectModel   = new SubjectModel();
        $this->gradeModel     = new GradeModel();
        $this->outcomeModel   = new OutcomeModel();
        $this->objectiveModel = new ObjectiveModel();
    }

    public function index()
    {
        $outcome_id = $this->request->getGet('outcome_id');

        $builder = $this->objectiveModel->select('objectives.*, outcomes.outcome_name')
            ->join('outcomes', 'outcomes.id = objectives.outcome_id', 'left');

        if ($outcome_id) {
            $builder = $builder->where('objectives.outcome_id', $outcome_id);
        }

        return view('objective/index', [
            'objective'  => $builder->findAll(),
            'outcome_id' => $outcome_id
        ]);
    }

    public function create()
    {
        $outcome_id = $this->request->getGet('outcome_id');

        return view('objective/form', [
            'outcome_id' => $outcome_id
        ]);
    }

    public function edit($id)
    {
        $objective = $this->objectiveModel->find($id);
        if (!$objective) {
            return redirect()->to('/outcome')->with('error', 'Objective tidak ditemukan.');
        }

        $outcome_id = $objective['outcome_id'];
        $outcome    = $this->outcomeModel->find($outcome_id);
        $subject_id = $outcome['subject_id'] ?? null;

        return view('objective/form', [
            'objective'  => $objective,
            'outcome_id' => $outcome_id,
            'subject_id' => $subject_id
        ]);
    }

    public function store()
    {
        $term = $this->request->getPost('term_id');
        $subject_id = $this->request->getPost('subject_id');

        $this->objectiveModel->insert([
            'outcome_id'     => $this->request->getPost('outcome_id'),
            'term_id'        => $term ? (int)$term : null,
            'objective_name' => $this->request->getPost('objective_name'),
        ]);

        return redirect()->to('/objective?outcome_id=' .
            $this->request->getPost('outcome_id') . '&subject_id=' . $subject_id);
    }

    public function update($id)
    {
        $term = $this->request->getPost('term_id');
        $subject_id = $this->request->getPost('subject_id');

        $this->objectiveModel->update($id, [
            'term_id'        => $term ? (int)$term : null,
            'objective_name' => $this->request->getPost('objective_name'),
        ]);

         return redirect()->to('/objective?outcome_id=' .
            $this->request->getPost('outcome_id') . '&subject_id=' . $subject_id);
    }

    public function delete($id)
    {
        $unit = $this->objectiveModel->find($id);

        $this->objectiveModel->delete($id);

        return redirect()->to('/objective?outcome_id=' .
            $unit['outcome_id']);
    }
}