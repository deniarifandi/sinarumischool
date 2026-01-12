<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Report extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        // Validate chapter via subject division
        // $chapter = $db->table('chapters')
        //     ->select('chapters.*, subjects.subject_name, subjects.division_id')
        //     ->join('subjects', 'subjects.id = chapters.subject_id')
        //     ->whereIn('subjects.division_id', $divisions)
        //     ->get()
        //     ->getRowArray();

        $chapters = $db->table('chapters')
            ->select('
                chapters.id,
                chapters.chapter_name,
                chapters.jp,
                subjects.subject_name,
                COALESCE(SUM(teaching_journals.jpspend), 0) AS total_jp_spent
            ')
            ->join(
                'teaching_journals',
                'teaching_journals.chapter_id = chapters.id',
                'left'
            )
            ->join('subjects','subjects.id = chapters.subject_id')
            ->groupBy('chapters.chapter_name')
            ->orderBy('chapters.chapter_name', 'ASC')
            ->get()
            ->getResultArray();
        // print_r($chapters);
        // exit();

        if (!$chapters) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('chapter_jp_table', [
            'chapters' => $chapters
        ]);

    }

}
