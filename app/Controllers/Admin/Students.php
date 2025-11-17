<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Students extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $division = session()->get('active_division');

        // Ambil semua siswa dalam division aktif
        $students = $db->table('students')
            ->select('students.*, classes.class_name')
            ->join('classes', 'classes.id = students.class_id', 'left')
            ->where('students.division_id', $division)
            ->orderBy('students.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/students/index', ['students' => $students]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $division = session()->get('active_division');

        // Ambil kelas berdasarkan divisi aktif
        $classes = $db->table('classes')
            ->where('division_id', $division)
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
        $division = session()->get('active_division');

        $student = $db->table('students')->where('id', $id)->get()->getRowArray();

        // Ambil kelas dalam division aktif
        $classes = $db->table('classes')
            ->where('division_id', $division)
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
}
