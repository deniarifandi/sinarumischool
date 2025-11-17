<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Subjects extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $divisions = session()->get('divisions');

        $subjects = $db->table('subjects')
            ->whereIn('division_id', $divisions)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/subjects/index', [
            'subjects' => $subjects
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        $allowedDivisions = session()->get('divisions');
        $activeDivision   = session()->get('active_division');

        $divisions = $db->table('divisions')
            ->whereIn('id', $allowedDivisions)
            ->get()
            ->getResultArray();

        return view('admin/subjects/create', [
            'divisions'       => $divisions,
            'active_division' => $activeDivision
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'division_id'  => $this->request->getPost('division_id'),
            'subject_code' => $this->request->getPost('subject_code'),
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description'),
        ];

        $db->table('subjects')->insert($data);

        return redirect()->to(base_url('admin/subjects'));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $subject = $db->table('subjects')->where('id', $id)->get()->getRowArray();

        $allowedDivisions = session()->get('divisions');

        $divisions = $db->table('divisions')
            ->whereIn('id', $allowedDivisions)
            ->get()
            ->getResultArray();

        return view('admin/subjects/edit', [
            'subject'   => $subject,
            'divisions' => $divisions,
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'division_id'  => $this->request->getPost('division_id'),
            'subject_code' => $this->request->getPost('subject_code'),
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description'),
        ];

        $db->table('subjects')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/subjects'));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Optional: prevent delete if subject is used in schedule, etc.

        $db->table('subjects')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/subjects'));
    }
}
