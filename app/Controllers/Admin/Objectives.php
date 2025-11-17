<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Objectives extends BaseController
{
    public function index($subchapter_id)
    {
        $db = \Config\Database::connect();

        // get subchapter
        $sub = $db->table('sub_chapters')
            ->where('id', $subchapter_id)
            ->get()
            ->getRowArray();

        // get chapter
        $chapter = $db->table('chapters')
            ->where('id', $sub['chapter_id'])
            ->get()
            ->getRowArray();

        // get subject
        $subject = $db->table('subjects')
            ->where('id', $chapter['subject_id'])
            ->get()
            ->getRowArray();

        // list objectives
        $objectives = $db->table('lesson_objectives')
            ->where('subchapter_id', $subchapter_id)
            ->orderBy('order_number', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/objectives/index', [
            'objectives' => $objectives,
            'sub'        => $sub,
            'chapter'    => $chapter,
            'subject'    => $subject
        ]);
    }

    public function create($subchapter_id)
    {
        return view('admin/objectives/create', [
            'subchapter_id' => $subchapter_id
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $subchapter_id = $this->request->getPost('subchapter_id');

        $data = [
            'subchapter_id' => $subchapter_id,
            'objective_code'=> $this->request->getPost('objective_code'),
            'objective_text'=> $this->request->getPost('objective_text'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        $db->table('lesson_objectives')->insert($data);

        return redirect()->to(base_url('admin/objectives/'.$subchapter_id));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $objective = $db->table('lesson_objectives')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        return view('admin/objectives/edit', [
            'objective' => $objective
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'objective_code'=> $this->request->getPost('objective_code'),
            'objective_text'=> $this->request->getPost('objective_text'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        $subchapter_id = $db->table('lesson_objectives')
            ->where('id', $id)->get()->getRowArray()['subchapter_id'];

        $db->table('lesson_objectives')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/objectives/'.$subchapter_id));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $objective = $db->table('lesson_objectives')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        $db->table('lesson_objectives')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/objectives/'.$objective['subchapter_id']));
    }
}
