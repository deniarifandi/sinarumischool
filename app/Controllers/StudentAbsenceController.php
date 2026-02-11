<?php

namespace App\Controllers;

use App\Models\StudentAbsenceModel;

class StudentAbsenceController extends BaseController
{
    protected StudentAbsenceModel $absenceModel;

    public function __construct()
    {
        $this->absenceModel = new StudentAbsenceModel();
    }

    public function index()
    {
        $date = $this->request->getGet('tanggal');

        $absences = $date
            ? $this->absenceModel->byDate($date)
            : $this->absenceModel->orderBy('tanggal', 'DESC')->findAll();

        return view('absence/index', [
            'absences' => $absences,
            'tanggal'  => $date,
        ]);
    }

    public function create()
    {
        return view('absence/form');
    }

    public function edit($id)
    {
        $absence = $this->absenceModel->find($id);

        if (!$absence) {
            return redirect()->to('absence')->with('error', 'Data not found');
        }

        return view('absence/form', [
            'absence' => $absence,
        ]);
    }

    public function save($id = null)
    {
        $data = [
            'tanggal'              => $this->request->getPost('tanggal'),
            'murid_id'             => (int) $this->request->getPost('murid_id'),
            'status'               => $this->request->getPost('status'),
            'absensi_keterangan'   => $this->request->getPost('absensi_keterangan'),
        ];

        if (!$data['tanggal'] || !$data['murid_id']) {
            return redirect()->back()->withInput()->with('error', 'Invalid data');
        }

        if ($id) {
            $this->absenceModel->update($id, $data);
        } else {
            $this->absenceModel->insert($data);
        }

        return redirect()->to('absence')->with('success', 'Saved');
    }

    public function delete($id)
    {
        $this->absenceModel->delete($id);

        return redirect()->to('absence')->with('success', 'Deleted');
    }
}
