<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Divisions extends BaseController
{
    public function index()
    {
        return view('admin/divisions/index');
    }

    /* =========================
        DATATABLE
    ========================== */
    public function datatable()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('divisions')
            ->select('id, division_name, description');

        return (new Datatable())->generate(
            $builder,
            'id',
            ['division_name', 'description'],
            ['division_name']
        );
    }

    public function create()
    {
        return view('admin/divisions/create');
    }

    public function store()
    {
        $db = \Config\Database::connect();

        if (!$this->validate([
            'division_name' => 'required|min_length[3]'
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'description'   => $this->request->getPost('description'),
        ];

        $db->table('divisions')->insert($data);

        return redirect()
            ->to(base_url('admin/divisions'))
            ->with('success', 'Division created successfully');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $division = $db->table('divisions')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$division) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        return view('admin/divisions/edit', [
            'division' => $division
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        if (!$this->validate([
            'division_name' => 'required|min_length[3]'
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'division_name' => $this->request->getPost('division_name'),
            'description'   => $this->request->getPost('description'),
        ];

        $db->table('divisions')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('admin/divisions'))
            ->with('success', 'Division updated successfully');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        // Prevent delete if division is used
        $used = $db->table('classes')->where('division_id', $id)->countAllResults()
              + $db->table('subjects')->where('division_id', $id)->countAllResults();

        if ($used > 0) {
            return redirect()
                ->to(base_url('admin/divisions'))
                ->with('error', 'Cannot delete division because it is still in use.');
        }

        $db->table('divisions')->where('id', $id)->delete();

        // Cleanup user_divisions
        $db->table('user_divisions')->where('division_id', $id)->delete();

        return redirect()
            ->to(base_url('admin/divisions'))
            ->with('success', 'Division deleted successfully');
    }
}
