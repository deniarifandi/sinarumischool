<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\OutcomeModel;

class Outcome extends BaseController
{
    protected $unitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel = new GradeModel();
        $this->outcomeModel = new OutcomeModel();
    }

    public function index()
    {
        $subject_id = $this->request->getGet('subject_id');

        $builder = $this->outcomeModel->select('outcomes.*, subjects.subject_name')
        ->join('subjects','subjects.id = outcomes.subject_id');

        if ($subject_id) {
            $builder = $builder->where('subject_id', $subject_id);
        }

        

        return view('outcome/index', [
            'outcome'     => $builder->findAll(),
            'subject_id' => $subject_id
        ]);
    }

    public function create()
    {
        $subject_id = $this->request->getGet('subject_id');

        $grade_list = $this->gradeModel->builder();
        $grades = $grade_list
            ->select('grades.*')
            ->join('divisions','grades.division_id = divisions.id','left')
            ->join('subjects','subjects.division_id = divisions.id','left')
            ->where('subjects.id', $subject_id)
            ->get()
            ->getResultArray();

        // return json_encode($grades);

        return view('outcome/form', [
            'subjectId' => $this->request->getGet('subject'),
            'gradeId'   => $this->request->getGet('grade'),
            'grades'    => $grades
        ]);
    }

    public function edit($id)
    {
        $subject_id = $this->outcomeModel->find($id);

        $subject_id = $subject_id['subject_id'];

        return view('outcome/form', [
            'outcome' => $this->outcomeModel->find($id),
            'subject_id'    => $subject_id
        ]);
    }

    public function store()
    {
        $this->outcomeModel->insert([
            'subject_id' => $this->request->getPost('subject_id'),
            'outcome_name'       => $this->request->getPost('outcome_name'),
        ]);

        return redirect()->to('/outcome?subject_id=' .
            $this->request->getPost('subject_id'));
    }

    public function update($id)
    {
        $this->outcomeModel->update($id, [
            'outcome_name'      => $this->request->getPost('outcome_name'),
        ]);

         return redirect()->to('/outcome?subject_id=' .
            $this->request->getPost('subject_id'));
    }

    public function delete($id)
    {
        $unit = $this->outcomeModel->find($id);

        $this->outcomeModel->delete($id);

        return redirect()->to('/outcome?subject_id=' .
            $unit['subject_id']);
    }
}