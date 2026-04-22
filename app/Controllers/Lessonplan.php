<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LessonplanModel;
use App\Models\UnitModel;
use App\Models\SubunitModel;
use App\Models\UserModel;
use App\Models\TujuanModel;

class Lessonplan extends BaseController
{
    protected $lessonplan;
    protected $unitModel;
    protected $subunitModel;
    protected $userModel;
    protected $tujuanModel;

    public function __construct()
    {
        $this->lessonplan = new LessonplanModel();
        $this->unitModel  = new UnitModel();
        $this->subunitModel = new SubunitModel();
        $this->userModel = new UserModel();
        $this->tujuanModel  = new TujuanModel();
    }

    // GET /lessonplan
    public function index()
    {
        $data['lessonplans'] = $this->lessonplan
            ->select('lessonplan.*, 
                      classes.class_name,
                      units.unit_nama as unit_name,
                      subunits.name as subunit_name')
            ->join('classes', 'classes.id = lessonplan.class_id', 'left')
            ->join('units', 'units.id = lessonplan.unit_id', 'left')
            ->join('subunit', 'subunits.id = lessonplan.subunit_id', 'left')
            ->findAll();

        return view('lessonplan/index', $data);
    }

    public function create()
    {    
        $user_id = session('id') ?? session('user_id');

        //print_r($this->userModel->getUserMainClass($user_id));
        $mainClass = $this->userModel->getUserMainClass($user_id);
        $tujuanAgama = $this->tujuanModel->getAgamaByGrade($mainClass[0]['grade']);
        $tujuanJati = $this->tujuanModel->getJatiByGrade($mainClass[0]['grade']);
        $tujuanLiterasi = $this->tujuanModel->getLiterasiByGrade($mainClass[0]['grade']);
        // print_r($tujuanAgama1);
        // exit();

        return view('lessonplan/form', [
            'units' => $this->unitModel->findAll(),
            'subunits'  => $this->subunitModel->findAll(),
            'mainClass' => $mainClass[0],
            'agama1List' => $tujuanAgama,
            'jatiList' => $tujuanJati,
            'literasiList' => $tujuanLiterasi
        ]);
    }


    public function edit($id)
    {
        $user_id = session('id') ?? session('user_id');

        //print_r($this->userModel->getUserMainClass($user_id));
        $mainClass = $this->userModel->getUserMainClass($user_id);

        $tujuanAgama = $this->tujuanModel->getAgamaByGrade($mainClass[0]['grade']);
        $tujuanJati = $this->tujuanModel->getJatiByGrade($mainClass[0]['grade']);
        $tujuanLiterasi = $this->tujuanModel->getLiterasiByGrade($mainClass[0]['grade']);

        return view('lessonplan/form', [
            'lessonplan' => $this->lessonplan->find($id),
            'units'      => $this->unitModel->findAll(),
            'subunits'   => $this->subunitModel->findAll(),
            'agama1List' => $tujuanAgama,
            'jatiList' => $tujuanJati,
            'literasiList' => $tujuanLiterasi
        ]);
    }

public function store()
{
    $this->lessonplan->insert($this->request->getPost());
    return redirect()->to('/lessonplan');
}

public function update($id)
{
    $this->lessonplan->update($id, $this->request->getPost());
    return redirect()->to('/lessonplan');
}

    // GET /lessonplan/{id}
    public function show($id)
    {
        $data = $this->lessonplan->find($id);

        if (!$data) {
            return $this->response->setStatusCode(404)
                                  ->setJSON(['message' => 'Data not found']);
        }

        return $this->response->setJSON($data);
    }

    // DELETE /lessonplan/{id}
      public function delete($id)
    {
        if (!$this->lessonplan->find($id)) {
            return redirect()->to('/lessonplan')
                             ->with('error', 'Data not found');
        }

        $this->lessonplan->delete($id);

        return redirect()->to('/lessonplan')
                         ->with('success', 'Deleted successfully');
    }
}