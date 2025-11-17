<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Divisions extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $divisions = $db->table('divisions')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/divisions/index', [
            'divisions' => $divisions
        ]);
    }

    public function create()
    {
        return view('admin/divisions/create');
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'description'   => $this->request->getPost('description'),
        ];

        $db->table('divisions')->insert($data);

        return redirect()->to(base_url('admin/divisions'));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $division = $db->table('divisions')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        return view('admin/divisions/edit', [
            'division' => $division
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'description'   => $this->request->getPost('description'),
        ];

        $db->table('divisions')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/divisions'));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Optional: prevent delete if used
        $countClasses = $db->table('classes')->where('division_id', $id)->countAllResults();
        $countSubjects = $db->table('subjects')->where('division_id', $id)->countAllResults();

        if ($countClasses > 0 || $countSubjects > 0) {
            return redirect()
                ->to(base_url('admin/divisions'))
                ->with('error', 'Cannot delete division because it still has classes or subjects.');
        }

        $db->table('divisions')->where('id', $id)->delete();

        // Remove related user_divisions
        $db->table('user_divisions')->where('division_id', $id)->delete();

        return redirect()->to(base_url('admin/divisions'));
    }
}
