<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;

class Unit extends BaseController
{
    protected $unitModel;
    protected $subjectModel;
    protected $gradeModel;

    public function __construct()
    {
        $this->unitModel    = new UnitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel   = new GradeModel();
    }

    public function index()
    {
        $subjectId = $this->request->getGet('subject_id');
        $gradeId   = $this->request->getGet('grade_id');

        $builder = $this->unitModel
            ->select('units.*, subjects.subject_name, grades.grade_name, terms.name as term_name')
            ->join('subjects', 'subjects.id = units.subject_id')
            ->join('grades', 'grades.id = units.grade_id', 'left')
            ->join('terms', 'terms.id = units.term_id', 'left')
            ->orderBy('grades.id');

        if ($subjectId) {
            $builder->groupStart()
                ->where('units.subject_id', $subjectId)
                ->orWhere('subjects.subject_name', 'All Subject')
                ->groupEnd();
        }

        if ($gradeId) {
            $builder->where('units.grade_id', $gradeId);
        }

        $grades = [];
        if ($subjectId) {
            $grades = $this->gradeModel
                ->select('grades.*')
                ->join('divisions', 'grades.division_id = divisions.id', 'left')
                ->join('subjects', 'subjects.division_id = divisions.id', 'left')
                ->where('subjects.id', $subjectId)
                ->where('grades.deleted_at', null)
                ->findAll();
        }

        $subjectName = null;
        if ($subjectId) {
            $subject = $this->subjectModel->find($subjectId);
            if ($subject) {
                $subjectName = $subject['subject_name'];
            }
        }

        return view('unit/index', [
            'units'        => $builder->findAll(),
            'subjectId'    => $subjectId,
            'subjectName'  => $subjectName,
            'gradeId'      => $gradeId,
            'grades'       => $grades,
        ]);
    }

    public function create()
    {
        $subject_id = $this->request->getGet('subject_id');

        $grade_list = $this->gradeModel->builder();
        $grades = $grade_list
            ->select('grades.*, subjects.subject_name')
            ->join('divisions', 'grades.division_id = divisions.id', 'left')
            ->join('subjects', 'subjects.division_id = divisions.id', 'left')
            ->where('subjects.id', $subject_id)
            ->where('grades.deleted_at', null)
            ->get()
            ->getResultArray();

        return view('unit/form', [
            'subjectId' => $subject_id,
            'grades'    => $grades,
        ]);
    }

    public function edit($id)
    {
        $unit = $this->unitModel->find($id);
        $subject_id = $unit['subject_id'];

        $grade_list = $this->gradeModel->builder();
        $grades = $grade_list
            ->select('grades.*, subjects.subject_name')
            ->join('divisions', 'grades.division_id = divisions.id', 'left')
            ->join('subjects', 'subjects.division_id = divisions.id', 'left')
            ->where('subjects.id', $subject_id)
            ->where('grades.deleted_at', null)
            ->get()
            ->getResultArray();

        return view('unit/form', [
            'unit'       => $unit,
            'grades'     => $grades,
            'subject_id' => $subject_id,
        ]);
    }

    public function store()
    {
        $this->unitModel->insert([
            'subject_id' => $this->request->getPost('subject_id'),
            'grade_id'   => $this->request->getPost('grade_id'),
            'term_id'    => null,
            'name'       => $this->request->getPost('name'),
        ]);

        return redirect()->to('/unit?subject_id=' . $this->request->getPost('subject_id'));
    }

    public function update($id)
    {
        $this->unitModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'grade_id'  => $this->request->getPost('grade_id'),
            'term_id'   => null,
        ]);

        return redirect()->to('/unit?subject_id=' . $this->request->getPost('subject_id'));
    }

    public function delete($id)
    {
        $unit = $this->unitModel->find($id);
        $this->unitModel->delete($id);

        return redirect()->to('/unit?subject_id=' . $unit['subject_id']);
    }
}
