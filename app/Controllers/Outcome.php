<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;
use App\Models\OutcomeModel;
use App\Models\SubunitModel;

class Outcome extends BaseController
{
    protected $unitModel;
    protected $subunitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->subunitModel = new SubunitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel = new GradeModel();
        $this->outcomeModel = new OutcomeModel();
    }

    public function index()
    {
        $subject_id = $this->request->getGet('subject_id');

        $builder = $this->outcomeModel->select('outcomes.*, subjects.subject_name, grades.grade_name')
            ->join('subjects', 'subjects.id = outcomes.subject_id')
            ->join('grades', 'grades.id = outcomes.grade_id', 'left');

        if ($subject_id) {
            $builder = $builder->where('subject_id', $subject_id);
        }

        $outcomes = $builder->orderBy('grades.grade_name', 'ASC')
            ->orderBy('outcomes.outcome_name', 'ASC')
            ->findAll();

        $subjectName = '-';
        if ($subject_id) {
            $subject = $this->subjectModel->find($subject_id);
            if ($subject) {
                $subjectName = $subject['subject_name'];
            }
        }

        return view('outcome/index', [
            'outcome'    => $outcomes,
            'subject_id' => $subject_id,
            'subject_name' => $subjectName
        ]);
    }

    public function create()
    {
        $subject_id = $this->request->getGet('subject_id');

        $grades = $this->gradeModel->builder()
            ->select('grades.*')
            ->join('divisions', 'grades.division_id = divisions.id', 'left')
            ->join('subjects', 'subjects.division_id = divisions.id')
            ->where('subjects.id', $subject_id)
            ->where('grades.deleted_at', null)
            ->distinct()
            ->get()
            ->getResultArray();

        return view('outcome/form', [
            'subject_id' => $subject_id,
            'grades'     => $grades
        ]);
    }

    public function edit($id)
    {
        $outcome = $this->outcomeModel->find($id);
        if (!$outcome) {
            return redirect()->to('/outcome')->with('error', 'Outcome tidak ditemukan.');
        }

        $subject_id = $outcome['subject_id'];

        $grades = $this->gradeModel->builder()
            ->select('grades.*')
            ->join('divisions', 'grades.division_id = divisions.id', 'left')
            ->join('subjects', 'subjects.division_id = divisions.id', 'left')
            ->where('subjects.id', $subject_id)
            ->where('grades.deleted_at', null)
            ->get()
            ->getResultArray();

        return view('outcome/form', [
            'outcome'    => $outcome,
            'subject_id' => $subject_id,
            'grades'     => $grades
        ]);
    }

    /**
     * AJAX: units for a subject (optionally filtered by grade).
     */
    public function units()
    {
        $subjectId = (int) $this->request->getGet('subject_id');
        $gradeId   = (int) $this->request->getGet('grade_id');

        $builder = $this->unitModel
            ->select('units.id, units.name')
            ->join('subjects', 'subjects.id = units.subject_id', 'left')
            ->groupStart()
                ->where('units.subject_id', $subjectId)
                ->orWhere('subjects.subject_name', 'All Subject')
            ->groupEnd()
            ->where('units.deleted_at', null);

        if ($gradeId) {
            $builder->where('units.grade_id', $gradeId);
        }

        $units = $builder->orderBy('units.name', 'ASC')->findAll();

        return $this->response->setJSON(['units' => $units]);
    }

    /**
     * AJAX: subunits for a given unit.
     */
    public function subunits()
    {
        $unitId = (int) $this->request->getGet('unit_id');

        $subunits = $unitId
            ? $this->subunitModel->getByUnit($unitId)
            : [];

        return $this->response->setJSON(['subunits' => $subunits]);
    }

    public function store()
    {
        $this->outcomeModel->insert([
            'subject_id'   => $this->request->getPost('subject_id'),
            'grade_id'     => $this->request->getPost('grade_id'),
            'outcome_name' => $this->request->getPost('outcome_name'),
        ]);

        return redirect()->to('/outcome?subject_id=' .
            $this->request->getPost('subject_id'));
    }

    public function update($id)
    {
        $this->outcomeModel->update($id, [
            'grade_id'     => $this->request->getPost('grade_id'),
            'outcome_name' => $this->request->getPost('outcome_name'),
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