<?php

namespace App\Controllers;

use App\Models\GradeBookModel;

class GradeBookController extends BaseController
{
    public function open()
    {
        $model = new GradeBookModel();

        $data = [
            'academic_year_id'   => $this->request->getPost('academic_year_id'),
            'term'               => $this->request->getPost('term'),
            'class_id'           => $this->request->getPost('class_id'),
            'subject_id'         => $this->request->getPost('subject_id'),
            'assessment_type_id' => $this->request->getPost('assessment_type_id'),
            'teacher_id'         => session('teacher_id'),
        ];

        $gradeBook = $model
            ->where($data)
            ->first();

        if (!$gradeBook) {
            $data['status'] = 'draft';
            $id = $model->insert($data);
            $gradeBook = $model->find($id);
        }

        return redirect()->to('/gradebook/' . $gradeBook['id']);
    }
}