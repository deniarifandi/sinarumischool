<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Chapters extends BaseController
{
    public function index($subject_id)
    {
        $db = \Config\Database::connect();

        // get subject info
        $subject = $db->table('subjects')
            ->where('id', $subject_id)
            ->get()
            ->getRowArray();

        // get chapters for subject
        $chapters = $db->table('chapters')
            ->where('subject_id', $subject_id)
            ->orderBy('order_number', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/chapters/index', [
            'chapters'  => $chapters,
            'subject'   => $subject
        ]);
    }

    public function create($subject_id)
    {
        return view('admin/chapters/create', [
            'subject_id' => $subject_id
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'subject_id'    => $this->request->getPost('subject_id'),
            'chapter_code'  => $this->request->getPost('chapter_code'),
            'chapter_name'  => $this->request->getPost('chapter_name'),
            'description'   => $this->request->getPost('description'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        $db->table('chapters')->insert($data);

        return redirect()->to(base_url('admin/chapters/'.$data['subject_id']));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $chapter = $db->table('chapters')->where('id', $id)->get()->getRowArray();

        return view('admin/chapters/edit', [
            'chapter' => $chapter
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'chapter_code'  => $this->request->getPost('chapter_code'),
            'chapter_name'  => $this->request->getPost('chapter_name'),
            'description'   => $this->request->getPost('description'),
            'order_number'  => $this->request->getPost('order_number'),
        ];

        // get subject to redirect back
        $subject_id = $db->table('chapters')->where('id', $id)->get()->getRowArray()['subject_id'];

        $db->table('chapters')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/chapters/'.$subject_id));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $chapter = $db->table('chapters')->where('id', $id)->get()->getRowArray();

        $db->table('chapters')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/chapters/'.$chapter['subject_id']));
    }
}
