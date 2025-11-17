<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Subchapters extends BaseController
{
    public function index($chapter_id)
    {
        $db = \Config\Database::connect();

        // Get chapter
        $chapter = $db->table('chapters')
            ->where('id', $chapter_id)
            ->get()
            ->getRowArray();

        // Get subject
        $subject = $db->table('subjects')
            ->where('id', $chapter['subject_id'])
            ->get()
            ->getRowArray();

        // List subchapters
        $subs = $db->table('sub_chapters')
            ->where('chapter_id', $chapter_id)
            ->orderBy('order_number', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/subchapters/index', [
            'subs'    => $subs,
            'chapter' => $chapter,
            'subject' => $subject
        ]);
    }

    public function create($chapter_id)
    {
        return view('admin/subchapters/create', [
            'chapter_id' => $chapter_id
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $chapter_id = $this->request->getPost('chapter_id');

        $data = [
            'chapter_id'   => $chapter_id,
            'sub_code'     => $this->request->getPost('sub_code'),
            'sub_name'     => $this->request->getPost('sub_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
        ];

        $db->table('sub_chapters')->insert($data);

        return redirect()->to(base_url('admin/subchapters/'.$chapter_id));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $sub = $db->table('sub_chapters')->where('id', $id)->get()->getRowArray();

        return view('admin/subchapters/edit', [
            'sub' => $sub
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'sub_code'     => $this->request->getPost('sub_code'),
            'sub_name'     => $this->request->getPost('sub_name'),
            'description'  => $this->request->getPost('description'),
            'order_number' => $this->request->getPost('order_number'),
        ];

        // Get chapter ID
        $chapter_id = $db->table('sub_chapters')
            ->where('id', $id)->get()->getRowArray()['chapter_id'];

        $db->table('sub_chapters')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/subchapters/'.$chapter_id));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $sub = $db->table('sub_chapters')->where('id', $id)->get()->getRowArray();

        $db->table('sub_chapters')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/subchapters/'.$sub['chapter_id']));
    }
}
