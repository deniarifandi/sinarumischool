<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\Datatable;

class Students extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $division = session()->get('divisions');

        // Ambil semua siswa dalam division aktif
        $students = $db->table('students')
            ->select('students.*, classes.class_name')
            ->join('classes', 'classes.id = students.class_id', 'left')
            // ->where('students.division_id', $division)
            ->orderBy('students.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/students/index', ['students' => $students]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $division = session()->get('divisions');

        // Ambil kelas berdasarkan divisi aktif
        $classes = $db->table('classes')
            ->whereIn('division_id', $division)
            ->get()
            ->getResultArray();

        return view('admin/students/create', [
            'classes' => $classes
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'division_id'  => session()->get('active_division'),
            'class_id'     => $this->request->getPost('class_id'),
            'student_code' => $this->request->getPost('student_code'),
            'name'         => $this->request->getPost('name'),
            'gender'       => $this->request->getPost('gender'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'address'      => $this->request->getPost('address'),
        ];

        $db->table('students')->insert($data);

        return redirect()->to(base_url('admin/students'));
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();
        $division = session()->get('divisions');

        $student = $db->table('students')->where('id', $id)->get()->getRowArray();

        // Ambil kelas dalam division terdaftar
        $classes = $db->table('classes')
            ->whereIn('division_id', $division)
            ->get()
            ->getResultArray();

        return view('admin/students/edit', [
            'student' => $student,
            'classes' => $classes
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'class_id'     => $this->request->getPost('class_id'),
            'student_code' => $this->request->getPost('student_code'),
            'name'         => $this->request->getPost('name'),
            'gender'       => $this->request->getPost('gender'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'address'      => $this->request->getPost('address'),
        ];

        $db->table('students')->where('id', $id)->update($data);

        return redirect()->to(base_url('admin/students'));
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('students')->where('id', $id)->delete();

        return redirect()->to(base_url('admin/students'));
    }

      public function datatable()
    {
        $db = \Config\Database::connect();

        $divisions = session()->get('divisions') ?? [];

        $builder = $db->table('students')
            ->select('students.id, students.student_code, students.name, students.gender, students.birthdate, classes.class_name')
            ->join('classes', 'classes.id = students.class_id', 'left');
            //->whereIn('students.division_id', $divisions);

        return (new Datatable())->generate(
            $builder,
            'students.id',
            ['student_code', 'name', 'gender', 'class_name', 'birthdate'], // searchable
            ['student_code', 'name', 'class_name', 'birthdate']           // orderable
        );
    }

}
