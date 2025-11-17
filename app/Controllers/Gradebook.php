<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Gradebook extends BaseController
{
    public function index()
{
    $db = \Config\Database::connect();
    $division = session()->get('active_division');
    $userId = session()->get('user_id');
    $role = session()->get('role');

    // Load filters
    $classes = $db->table('classes')->where('division_id', $division)->get()->getResultArray();
    $subjects = $db->table('subjects')->where('division_id', $division)->get()->getResultArray();

    // Build main grade query
    $builder = $db->table('gradebook')
        ->select('gradebook.*, students.name as student_name, subjects.subject_name, classes.class_name')
        ->join('students', 'students.id = gradebook.student_id')
        ->join('subjects', 'subjects.id = gradebook.subject_id')
        ->join('classes', 'classes.id = gradebook.class_id')
        ->where('gradebook.division_id', $division);

    // Apply filters
    if ($this->request->getGet('class_id')) {
        $builder->where('gradebook.class_id', $this->request->getGet('class_id'));
    }

    if ($this->request->getGet('subject_id')) {
        $builder->where('gradebook.subject_id', $this->request->getGet('subject_id'));
    }

    // teacher-only restriction
    if ($role == 'guru') {
        $builder->where('gradebook.teacher_id', $userId);
    }

    $grades = $builder->orderBy('gradebook.id', 'DESC')->get()->getResultArray();

    return view('gradebook/index', [
        'grades'   => $grades,
        'classes'  => $classes,
        'subjects' => $subjects
    ]);
}


    public function input()
    {
        $db = \Config\Database::connect();
        $division = session()->get('active_division');

        // Load classes in division
        $classes = $db->table('classes')
            ->where('division_id', $division)
            ->get()->getResultArray();

        // Load subjects in division
        $subjects = $db->table('subjects')
            ->where('division_id', $division)
            ->get()->getResultArray();

        return view('gradebook/input', [
            'classes' => $classes,
            'subjects'=> $subjects
        ]);
    }

    public function save()
    {
        $db = \Config\Database::connect();

        $studentIds = $this->request->getPost('student_id');
        $scores     = $this->request->getPost('score');

        $data = [
            'class_id'      => $this->request->getPost('class_id'),
            'subject_id'    => $this->request->getPost('subject_id'),
            'chapter_id'    => $this->request->getPost('chapter_id'),
            'subchapter_id' => $this->request->getPost('subchapter_id'),
            'objective_id'  => $this->request->getPost('objective_id'),
            'division_id'   => session()->get('active_division'),
            'teacher_id'    => session()->get('user_id'),
        ];

        foreach ($studentIds as $index => $sid) {
            $row = $data;
            $row['student_id'] = $sid;
            $row['score']      = $scores[$index];

            // Insert OR update score
            $existing = $db->table('gradebook')
                ->where([
                    'student_id'   => $sid,
                    'class_id'     => $data['class_id'],
                    'subject_id'   => $data['subject_id'],
                    'chapter_id'   => $data['chapter_id'],
                    'subchapter_id'=> $data['subchapter_id'],
                    'objective_id' => $data['objective_id'],
                ])->get()->getRowArray();

            if ($existing) {
                $db->table('gradebook')->where('id', $existing['id'])->update($row);
            } else {
                $db->table('gradebook')->insert($row);
            }
        }

        return redirect()->to(base_url('gradebook'));
    }
}
