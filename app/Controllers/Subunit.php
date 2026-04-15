<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitModel;
use App\Models\SubunitModel;
use App\Models\SubjectModel;
use App\Models\GradeModel;

class Subunit extends BaseController
{
    protected $unitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->subjectModel = new SubjectModel();
        $this->gradeModel = new GradeModel();
        $this->subunitModel = new SubunitModel();
    }

    public function index()
    {
        $unit_id = $this->request->getGet('unit_id');

        $builder = $this->subunitModel->select('units.name, subunits.*')
        ->join('units','units.id = subunits.unit_id');

        // print_r($builder->get()->getResult());
        // exit();
        
        if ($unit_id) {
            $builder = $builder->where('unit_id', $unit_id);
        }

        return view('subunit/index', [
            'subunits'     => $builder->findAll(),
            'unit_id' => $unit_id
        ]);
    }

    public function create()
    {
        $subject_id = $this->request->getGet('subject_id');

        $grade_list = $this->gradeModel->builder();
        $grades = $grade_list
            ->select('grades.*')
            ->join('divisions','grades.division_id = divisions.id','left')
            ->join('subjects','subjects.division_id = divisions.id','left')
            ->where('subjects.id', $subject_id)
            ->get()
            ->getResultArray();

        // return json_encode($grades);

        return view('subunit/form', [
            'subjectId' => $this->request->getGet('subject'),
            'gradeId'   => $this->request->getGet('grade'),
            'grades'    => $grades
        ]);
    }

    public function edit($id)
    {
        $builder = $this->subunitModel->select('subunits.*')->find($id);
        // ->where('subunits.id',$id)
        // ->get()->getResult();
        
        return view('subunit/form', [
            'subunit' => $builder
        ]);
    }

    public function store()
    {
        $this->subunitModel->insert([
            'unit_id'       => $this->request->getPost('unit_id'),
            'subunit_name'  => $this->request->getPost('subunit_name'),
        ]);

        return redirect()->to('/subunit?unit_id=' .
            $this->request->getPost('unit_id'));
    }

    public function update($id)
    {
        $this->subunitModel->update($id, [
            'unit_id'       => $this->request->getPost('unit_id'),
            'subunit_name'      => $this->request->getPost('subunit_name'),
        ]);

         return redirect()->to('/subunit?unit_id=' .
            $this->request->getPost('unit_id'));
    }

    public function delete($id)
    {
        $subunit = $this->subunitModel->find($id);
        $unit_id = $subunit['unit_id'];
        $this->subunitModel->delete($id);

        return redirect()->to('/subunit?unit_id='.
            $unit_id);
    }
}