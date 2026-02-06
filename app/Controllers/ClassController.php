<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\GradeModel;

class ClassController extends BaseController
{
    protected $classModel;
    protected $gradeModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->gradeModel = new GradeModel(); // untuk dropdown grade
    }

    public function index()
    {
        $divisiId = $this->request->getGet('divisi');
        //$grade    = $this->request->getGet('grade');

        if (!$divisiId) {
            return redirect()->to('/')->with('error', 'Division not found');
        }

        $classes = $this->classModel->byDivision($divisiId);

        //$grades = $this->gradeModel->byDivision($divisiId);

        return view('class/index', [
            'classes'  => $classes,
          //  'grades'   => $grades,
            'divisiId' => $divisiId,
          //  'grade'    => $grade
        ]);
    }

    // CREATE FORM
    public function create()
    {
        $divisiId = $this->request->getGet('divisi');
        $grades   = $this->gradeModel->byDivision($divisiId);

        return view('class/form', [
            'divisiId' => $divisiId,
            'grades'   => $grades
        ]);
    }

    // EDIT FORM
    public function edit($id)
    {
        $divisiId = $this->request->getGet('divisi');
        $class = $this->classModel->find($id);

        if (!$class || $class['division_id'] != $divisiId) {
            return redirect()->to('class?divisi='.$divisiId)
                ->with('error', 'Class not found');
        }

        $grades = $this->gradeModel->byDivision($divisiId);

        return view('class/form', [
            'class'    => $class,
            'grades'   => $grades,
            'divisiId' => $divisiId
        ]);
    }

    // STORE + UPDATE
    public function save($id = null)
    {
        $divisiId = $this->request->getPost('division_id');

        $data = [
            'division_id' => $divisiId,
            'grade'       => $this->request->getPost('grade'),
            'class_name'  => $this->request->getPost('class_name'),
            'description' => $this->request->getPost('description'),
        ];

        if (!$data['class_name'] || !$data['grade']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Grade and class name are required');
        }

        if ($id) {
            $class = $this->classModel->find($id);

            if (!$class || $class['division_id'] != $divisiId) {
                return redirect()->to('class?divisi='.$divisiId)
                    ->with('error', 'Invalid access');
            }

            $this->classModel->update($id, $data);
            $msg = 'Class updated successfully';
        } else {
            $this->classModel->insert($data);
            $msg = 'Class created successfully';
        }

        return redirect()
            ->to('class?divisi='.$divisiId)
            ->with('success', $msg);
    }

    public function delete($id)
    {
        $divisiId = $this->request->getPost('divisi');

        $class = $this->classModel->find($id);
        if ($class && $class['division_id'] == $divisiId) {
            $this->classModel->delete($id);
        }

        return redirect()
            ->to('class?divisi='.$divisiId)
            ->with('success', 'Class deleted');
    }
}
