<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;

class Unit extends BaseController
{
    protected $unitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
    }

    public function index()
    {
        $subjectId = $this->request->getGet('subject');
        $gradeId   = $this->request->getGet('grade');

        $builder = $this->unitModel;

        if ($subjectId) {
            $builder = $builder->where('subject_id', $subjectId);
        }

        if ($gradeId) {
            $builder = $builder->where('grade_id', $gradeId);
        }

        return view('unit/index', [
            'units'     => $builder->findAll(),
            'subjectId' => $subjectId,
            'gradeId'   => $gradeId,
        ]);
    }

    public function create()
    {
        return view('unit/form', [
            'subjectId' => $this->request->getGet('subject'),
            'gradeId'   => $this->request->getGet('grade'),
        ]);
    }

    public function edit($id)
    {
        return view('unit/form', [
            'unit' => $this->unitModel->find($id),
        ]);
    }

    public function store()
    {
        $this->unitModel->insert([
            'subject_id' => $this->request->getPost('subject_id'),
            'grade_id'   => $this->request->getPost('grade_id'),
            'name'       => $this->request->getPost('name'),
        ]);

        return redirect()->to('/unit?subject=' .
            $this->request->getPost('subject_id') .
            '&grade=' .
            $this->request->getPost('grade_id'));
    }

    public function update($id)
    {
        $this->unitModel->update($id, [
            'name' => $this->request->getPost('name'),
        ]);

        return redirect()->to('/unit?subject=' .
            $this->request->getPost('subject_id') .
            '&grade=' .
            $this->request->getPost('grade_id'));
    }

    public function delete($id)
    {
        $unit = $this->unitModel->find($id);

        $this->unitModel->delete($id);

        return redirect()->to('/unit?subject=' .
            $unit['subject_id'] .
            '&grade=' .
            $unit['grade_id']);
    }
}