<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Classes extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // All accessible divisions of the user
        $divisions = session()->get('divisions');

        $classes = $db->table('classes')
            ->whereIn('division_id', $divisions)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/classes/index', [
            'classes' => $classes
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        $allowedDivisions = session()->get('divisions');
        // $activeDivision   = session()->get('active_division');

        // dropdown hanya berisi division yang user punya
        $divisions = $db->table('divisions')
            // ->whereIn('id', $allowedDivisions)
            ->get()
            ->getResultArray();

        return view('admin/classes/create', [
            'divisions'       => $divisions,
            'active_division' => $activeDivision
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'division_id' => $this->request->getPost('division_id'),
            'class_name'  => $this->request->getPost('class_name'),
            'description' => $this->request->getPost('description'),
        ];

        $db->table('classes')->insert($data);

        return redirect()->to(base_url('admin/classes'));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $class = $db->table('classes')->where('id', $id)->get()->getRowArray();

        $allowedDivisions = session()->get('divisions');

        $divisions = $db->table('divisions')
            ->whereIn('id', $allowedDivisions)
            ->get()
            ->getResultArray();

        return view('admin/classes/edit', [
            'class'     => $class,
            'divisions' => $divisions,
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'division_id' => $this->request->getPost('division_id'),
            'class_name'  => $this->request->getPost('class_name'),
            'description' => $this->request->getPost('description'),
        ];

        $db->table('classes')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/classes'));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $count = $db->table('students')->where('class_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()
                ->to(base_url('admin/classes'))
                ->with('error', 'Cannot delete class because it has students.');
        }

        $db->table('classes')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/classes'));
    }

    public function datatable()
{
    $db = \Config\Database::connect();

    $builder = $db->table('classes')
        ->select('id, class_name, description');

    return (new \App\Libraries\Datatable())->generate(
        $builder,
        'id',
        ['class_name', 'description'], // searchable
        ['class_name', 'description']  // orderable
    );
}
}
