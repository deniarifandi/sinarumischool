<?php

namespace App\Controllers;

use App\Models\GradeModel;
use App\Models\DivisionModel;

class GradeController extends BaseController
{
    protected $gradeModel;
    protected $divisiModel;

    public function __construct()
    {
        $this->gradeModel = new GradeModel();
        $this->divisiModel = new DivisionModel();
    }

    public function index()
    {
        $divisiId = $this->request->getGet('divisi');
        $division = $this->divisiModel->getDivisionDetail($divisiId);

        $grades = $this->gradeModel->byDivision($divisiId);

        // print_r($division[0]['division_name']);
        // exit();

        return view('grade/index', [
            'grades'   => $grades,
            'division' => $division
        ]);
    }

    // CREATE FORM
    public function create()
    {
        $divisiId = $this->request->getGet('divisi');

        return view('grade/form', [
            'divisiId' => $divisiId
        ]);
    }

    // EDIT FORM
    public function edit($id)
    {
        $divisiId = $this->request->getGet('divisi');
        $grade = $this->gradeModel->find($id);

        if (!$grade || $grade['division_id'] != $divisiId) {
            return redirect()->to('grade?divisi='.$divisiId)
                ->with('error', 'Grade not found');
        }

        return view('grade/form', [
            'grade'    => $grade,
            'divisiId' => $divisiId
        ]);
    }

    // STORE + UPDATE
    public function save($id = null)
    {
        $divisiId = $this->request->getPost('division_id');

        $data = [
            'division_id' => $divisiId,
            'grade_name'  => $this->request->getPost('grade_name'),
            'sort_order'  => (int) $this->request->getPost('sort_order')
        ];

        if (!$data['grade_name']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Grade name is required');
        }

        // UPDATE
        if ($id) {
            $grade = $this->gradeModel->find($id);

            if (!$grade || $grade['division_id'] != $divisiId) {
                return redirect()->to('grade?divisi='.$divisiId)
                    ->with('error', 'Invalid access');
            }

            $this->gradeModel->update($id, $data);
            $msg = 'Grade updated successfully';

        // CREATE
        } else {
            $this->gradeModel->insert($data);
            $msg = 'Grade created successfully';
        }

        return redirect()
            ->to('grade?divisi='.$divisiId)
            ->with('success', $msg);
    }

    public function delete($id)
    {
        $divisiId = $this->request->getPost('divisi');

        $grade = $this->gradeModel->find($id);
        if ($grade && $grade['division_id'] == $divisiId) {
            $this->gradeModel->delete($id);
        }

        return redirect()
            ->to('grade?divisi='.$divisiId)
            ->with('success', 'Grade deleted');
    }
}
