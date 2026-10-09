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
        $subject_id = $this->request->getGet('subject_id');

        $builder = $this->objectiveModel->select('objectives.*, outcomes.outcome_name, outcomes.subject_id')
            ->join('outcomes', 'outcomes.id = objectives.outcome_id', 'left');

        // Jangan gunakan truthy-check di sini: `outcome_id=0` sebelumnya
        // dianggap false sehingga semua objective ikut ditampilkan.
        if ($outcome_id !== null && $outcome_id !== '') {
            $builder = $builder->where('objectives.outcome_id', (int) $outcome_id);
        }

        // outcome_id lama bisa bernilai 0 pada data existing. Batasi juga
        // berdasarkan subject agar outcome dari subject lain tidak ikut
        // menggandakan objective melalui JOIN.
        if ($subject_id !== null && $subject_id !== '') {
            $builder = $builder->where('outcomes.subject_id', (int) $subject_id);
        }

        // Subject name untuk ditampilkan di header
        $subject_name = null;
        if ($subject_id) {
            $subject = $this->subjectModel->find($subject_id);
            if ($subject) {
                $subject_name = $subject['subject_name'];
            }
        }

        // Outcome name untuk ditampilkan di header
        $outcome_name = null;
        if ($outcome_id !== null && $outcome_id !== '') {
            $outcome = $this->outcomeModel->find($outcome_id);
            if ($outcome) {
                $outcome_name = $outcome['outcome_name'];
            }
        }

        return view('objective/index', [
            'objective'    => $builder->orderBy('objectives.id', 'ASC')->findAll(),
            'outcome_id'   => $outcome_id,
            'outcome_name' => $outcome_name,
            'subject_id'   => $subject_id,
            'subject_name' => $subject_name
        ]);
    }

    public function create()
    {
        $outcome_id = $this->request->getGet('outcome_id');
        $subject_id = $this->request->getGet('subject_id');

        return view('objective/form', [
            'outcome_id' => $outcome_id,
            'subject_id' => $subject_id
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
