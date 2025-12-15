<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Subjects extends BaseController
{
    public function index()
    {
        // Data loaded via DataTables
        return view('admin/subjects/index');
    }

    public function datatable()
    {
        $db = \Config\Database::connect();

        $divisions = session()->get('divisions') ?? [];

        $builder = $db->table('subjects')
            ->select('subjects.id, subjects.subject_code, subjects.subject_name, subjects.description')
            ->whereIn('subjects.division_id', $divisions);

        return (new Datatable())->generate(
            $builder,
            'subjects.id',
            ['subject_code', 'subject_name', 'description'], // searchable
            ['subject_code', 'subject_name']                 // orderable
        );
    }

    public function create()
    {
        $db = \Config\Database::connect();

        $allowedDivisions = session()->get('divisions') ?? [];
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

        return redirect()
            ->to(base_url('admin/subjects'))
            ->with('success', 'Subject added successfully');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $subject = $db->table('subjects')
            ->where('id', $id)
            ->whereIn('division_id', $divisions)
            ->get()
            ->getRowArray();

        if (!$subject) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $divisionsList = $db->table('divisions')
            ->whereIn('id', $divisions)
            ->get()
            ->getResultArray();

        return view('admin/subjects/edit', [
            'subject'   => $subject,
            'divisions' => $divisionsList,
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $exists = $db->table('subjects')
            ->where('id', $id)
            ->whereIn('division_id', $divisions)
            ->countAllResults();

        if (!$exists) {
            throw new \CodeIgniter\Exceptions\PageForbiddenException();
        }

        $data = [
            'division_id'  => $this->request->getPost('division_id'),
            'subject_code' => $this->request->getPost('subject_code'),
            'subject_name' => $this->request->getPost('subject_name'),
            'description'  => $this->request->getPost('description'),
        ];

        $db->table('subjects')->where('id', $id)->update($data);

        return redirect()
            ->to(base_url('admin/subjects'))
            ->with('success', 'Subject updated successfully');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $divisions = session()->get('divisions') ?? [];

        $db->table('subjects')
            ->where('id', $id)
            ->whereIn('division_id', $divisions)
            ->delete();

        return redirect()
            ->to(base_url('admin/subjects'))
            ->with('success', 'Subject deleted successfully');
    }
}
