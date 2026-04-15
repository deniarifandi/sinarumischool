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

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel = new GradeModel();
         $this->outcomeModel = new OutcomeModel();
        $this->objectiveModel = new ObjectiveModel();
    }

    public function index()
    {
        $outcome_id = $this->request->getGet('outcome_id');

        $builder = $this->objectiveModel->select('objectives.*, outcomes.outcome_name')
        ->join('outcomes','outcomes.id = objectives.outcome_id');

        if ($outcome_id) {
            $builder = $builder->where('outcome_id', $outcome_id);
        }

        return view('objective/index', [
            'objective'     => $builder->findAll(),
            'outcome_id' => $outcome_id
        ]);
    }

    public function create()
    {
        $outcome_id = $this->request->getGet('outcome_id');

        return view('objective/form', [
            'outcome_id'    => $outcome_id
        ]);
    }

    public function edit($id)
    {
        $objective = $this->objectiveModel->find($id);

        $outcome_id = $objective['outcome_id'];

        return view('objective/form', [
            'objective' => $this->objectiveModel->find($id),
            'outcome_id'    => $outcome_id
        ]);
    }

    public function store()
    {
        $this->objectiveModel->insert([
            'outcome_id' => $this->request->getPost('outcome_id'),
            'objective_name'       => $this->request->getPost('objective_name'),
        ]);

        return redirect()->to('/objective?outcome_id=' .
            $this->request->getPost('outcome_id'));
    }

    public function update($id)
    {
        $this->objectiveModel->update($id, [
            'objective_name'      => $this->request->getPost('objective_name'),
        ]);

         return redirect()->to('/objective?outcome_id=' .
            $this->request->getPost('outcome_id'));
    }

    public function delete($id)
    {
        $unit = $this->objectiveModel->find($id);

        $this->objectiveModel->delete($id);

        return redirect()->to('/objective?outcome_id=' .
            $unit['outcome_id']);
    }
}