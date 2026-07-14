<?php

namespace App\Controllers;

use App\Models\StudentScoreModel;

class StudentScoreController extends BaseController
{
    public function save($gradeBookId)
    {
        $model = new StudentScoreModel();

        $scores = $this->request->getPost('scores');

        foreach ($scores as $studentId => $row) {

            $existing = $model
                ->where([
                    'grade_book_id' => $gradeBookId,
                    'student_id'    => $studentId
                ])
                ->first();

            $data = [
                'grade_book_id' => $gradeBookId,
                'student_id'    => $studentId,
                'score'         => $row['score'],
                'remed_score'   => $row['remed'],
            ];

            if ($existing) {
                $model->update($existing['id'], $data);
            } else {
                $model->insert($data);
            }
        }

        return redirect()->back()->with('success', 'Scores saved.');
    }
}