<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;

class Unit extends BaseController
{
    protected $unitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel = new GradeModel();
    }

    public function index()
    {
        $subjectId = $this->request->getGet('subject_id');

        $builder = $this->unitModel->select('units.*, subjects.subject_name, grades.grade_name')
        ->join('subjects','subjects.id = units.subject_id')
        ->join('grades','grades.id = units.grade_id')
        ->orderBy('grades.id');
        if ($subjectId) {
            $builder = $builder->where('subject_id', $subjectId);
        }


        return view('unit/index', [
            'units'     => $builder->findAll(),
            'subjectId' => $subjectId
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
            ->where('grades.deleted_at',null)
            ->get()
            ->getResultArray();

        // return json_encode($grades);

        return view('unit/form', [
            'subjectId' => $this->request->getGet('subject'),
            'gradeId'   => $this->request->getGet('grade'),
            'grades'    => $grades
        ]);
    }

    public function edit($id)
    {
        $subject_id = $this->unitModel->find($id);

        $subject_id = $subject_id['subject_id'];

        $grade_list = $this->gradeModel->builder();
        $grades = $grade_list
            ->select('grades.*')
            ->join('divisions','grades.division_id = divisions.id','left')
            ->join('subjects','subjects.division_id = divisions.id','left')
            ->where('subjects.id', $subject_id)
             ->where('grades.deleted_at',null)
            ->get()
            ->getResultArray();

        // print_r($subject_id);
        // exit();

        return view('unit/form', [
            'unit' => $this->unitModel->find($id),
            'grades'    => $grades,
            'subject_id'    => $subject_id
        ]);
    }

    public function store()
    {
        $this->unitModel->insert([
            'subject_id' => $this->request->getPost('subject_id'),
            'grade_id'   => $this->request->getPost('grade_id'),
            'name'       => $this->request->getPost('name'),
        ]);

        return redirect()->to('/unit?subject_id=' .
            $this->request->getPost('subject_id'));
    }

    public function update($id)
    {
        $this->unitModel->update($id, [
            'name'      => $this->request->getPost('name'),
            'grade_id'  => $this->request->getPost('grade_id'),
        ]);

         return redirect()->to('/unit?subject_id=' .
            $this->request->getPost('subject_id'));
    }

    public function delete($id)
    {
        $unit = $this->unitModel->find($id);

        $this->unitModel->delete($id);

        return redirect()->to('/unit?subject_id=' .
            $unit['subject_id']);
    }
}