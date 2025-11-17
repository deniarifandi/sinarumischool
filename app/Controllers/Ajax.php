<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Ajax extends BaseController
{
    public function getChapters($subject_id)
    {
        $db = \Config\Database::connect();
        $chapters = $db->table('chapters')
            ->where('subject_id', $subject_id)
            ->orderBy('order_number', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($chapters);
    }

    public function getSubchapters($chapter_id)
    {
        $db = \Config\Database::connect();
        $subs = $db->table('sub_chapters')
            ->where('chapter_id', $chapter_id)
            ->orderBy('order_number', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($subs);
    }

    public function getObjectives($subchapter_id)
    {
        $db = \Config\Database::connect();
        $objs = $db->table('lesson_objectives')
            ->where('subchapter_id', $subchapter_id)
            ->orderBy('order_number', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($objs);
    }

    public function getStudents($class_id)
    {
        $db = \Config\Database::connect();
        $students = $db->table('students')
            ->where('class_id', $class_id)
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON($students);
    }

}
