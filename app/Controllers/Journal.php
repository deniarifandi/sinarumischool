<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Journal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $userRole = session()->get('role');
        $userId   = session()->get('id');

        // Admin sees all, teacher sees only their own
        $builder = $db->table('teaching_journals')
            ->select('teaching_journals.*, classes.class_name, subjects.subject_name')
            ->join('classes', 'classes.id = teaching_journals.class_id')
            ->join('subjects', 'subjects.id = teaching_journals.subject_id')
            ->where('teaching_journals.user_id', $userId)
            ->orderBy('date', 'DESC');

        if ($userRole == 'guru') {
            $builder->where('teaching_journals.user_id', $userId);
        }

        $journals = $builder->get()->getResultArray();

        return view('journal/index', [
            'journals' => $journals
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $division = session()->get('divisions');
        // print_r($division);
        // exit();
        // classes belonging to division
        $classes = $db->table('classes')
            ->whereIn('division_id', $division)
            ->get()->getResultArray();

        // subjects belonging to division
        $subjects = $db->table('subjects')
            ->whereIn('division_id', $division)
            ->get()->getResultArray();

        return view('journal/create', [
            'classes'  => $classes,
            'subjects' => $subjects
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'user_id'      => session()->get('id'),
            // 'division_id'  => session()->get('active_division'),
            'class_id'     => $this->request->getPost('class_id'),
            'subject_id'   => $this->request->getPost('subject_id'),
            'chapter_id'   => $this->request->getPost('chapter_id'),
            'subchapter_id'=> $this->request->getPost('subchapter_id'),
            'date'         => $this->request->getPost('date'),
            // 'time_start'   => $this->request->getPost('time_start'),
            // 'time_end'     => $this->request->getPost('time_end'),
            'activities'   => $this->request->getPost('activities'),
            'notes'        => $this->request->getPost('notes'),
        ];

        $db->table('teaching_journals')->insert($data);

        return redirect()->to(base_url('journal'));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $journal = $db->table('teaching_journals')->where('id', $id)->get()->getRowArray();
        $division = $journal['division_id'];

        $classes = $db->table('classes')->where('division_id', $division)->get()->getResultArray();
        $subjects = $db->table('subjects')->where('division_id', $division)->get()->getResultArray();

        return view('journal/edit', [
            'journal'  => $journal,
            'classes'  => $classes,
            'subjects' => $subjects,
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'class_id'     => $this->request->getPost('class_id'),
            'subject_id'   => $this->request->getPost('subject_id'),
            'chapter_id'   => $this->request->getPost('chapter_id'),
            'subchapter_id'=> $this->request->getPost('subchapter_id'),
            'date'         => $this->request->getPost('date'),
            'time_start'   => $this->request->getPost('time_start'),
            'time_end'     => $this->request->getPost('time_end'),
            'activities'   => $this->request->getPost('activities'),
            'notes'        => $this->request->getPost('notes'),
        ];

        $db->table('teaching_journals')->where('id', $id)->update($data);

        return redirect()->to(base_url('journal'));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $db->table('teaching_journals')->where('id', $id)->delete();

        return redirect()->to(base_url('journal'));
    }
}
