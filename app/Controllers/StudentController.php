<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;

class StudentController extends BaseController
{
    protected StudentModel $studentModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
    }

    public function index()
    {
        $divisionId = (int) $this->request->getGet('division');

        $students = $this->studentModel->studentDetail($divisionId);


        // echo json_encode($students);
        // exit();
        return view('student/index', [
            'students'   => $students,
            'divisionId' => $divisionId,
        ]);
    }

    public function create()
    {
        $divisionId = (int) $this->request->getGet('division');

        $classes = $this->classModel->byDivision($divisionId);

        return view('student/form',[ 'divisionId' => $divisionId ,'classes' => $classes ]);
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        $divisionId = $this->request->getGet('division');

        if (!$student) {
            return redirect()->to('student')->with('error', 'Student not found');
        }

        $classes = $this->classModel->byDivision($divisionId);

       // return view('student/form',[ 'divisionId' => $divisionId ]);

        return view('student/form', [
            'student'   => $student,
            'divisionId'  => $divisionId,
            'classes' => $classes 
        ]);
    }

    public function save($id = null)
    {
        $data = [
            'division_id'   => (int) $this->request->getPost('division_id'),
            'class_id'      => (int) $this->request->getPost('class_id'),
            'name'          => trim((string) $this->request->getPost('name')),
            'gender'        => $this->request->getPost('gender'),
            'birthdate'     => $this->request->getPost('birthdate'),
            'student_code'  => trim((string) $this->request->getPost('student_code')),
            'address'       => $this->request->getPost('address'),
            'murid_agama'   => $this->request->getPost('murid_agama'),
        ];

        if ($data['name'] === '') {
            return redirect()->back()->withInput()->with('error', 'Name is required');
        }

        if ($id) {
            $this->studentModel->update($id, $data);
        } else {
            $this->studentModel->insert($data);
        }

        return redirect()->to('student?division=' . $data['division_id'])
            ->with('success', 'Saved');
    }

    public function delete($id)
    {
        $this->studentModel->delete($id);

        return redirect()->back()->with('success', 'Deleted');
    }
}
