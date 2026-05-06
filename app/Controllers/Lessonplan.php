<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LessonplanModel;
use App\Models\UnitModel;
use App\Models\SubunitModel;
use App\Models\UserModel;
use App\Models\TujuanModel;
use App\Models\ObjectiveModel;

class Lessonplan extends BaseController
{
    protected $lessonplan;
    protected $unitModel;
    protected $subunitModel;
    protected $userModel;
    protected $tujuanModel;
    protected $objectiveModel;

    public function __construct()
    {
        $this->lessonplan   = new LessonplanModel();
        $this->unitModel    = new UnitModel();
        $this->subunitModel = new SubunitModel();
        $this->userModel    = new UserModel();
        $this->tujuanModel  = new TujuanModel();
        $this->objectiveModel = new ObjectiveModel();
    }

    // GET /lessonplan
    public function index()
    {
        $data['lessonplans'] = $this->lessonplan
            ->select('lessonplan.*, 
                      classes.class_name,
                      units.name as unit_name,
                      subunits.subunit_name as subunit_name')
            ->join('classes', 'classes.id = lessonplan.class_id', 'left')
            ->join('units', 'units.id = lessonplan.unit_id', 'left')
            ->join('subunits', 'subunits.id = lessonplan.subunit_id', 'left')
            ->where('lessonplan.subject_id',$_GET['subject_id'])
            ->findAll();

        return view('lessonplan/index', $data);
    }

    // GET /lessonplan/create
  public function create()
{
    $user_id = session('id') ?? session('user_id');
    $subject_id = $this->request->getGet('subject_id');

    $mainClass = $this->userModel->getUserMainClass($user_id);

    if (empty($mainClass)) {
        throw new \RuntimeException('User has no main class');
    }

    if (!$subject_id) {
        throw new \RuntimeException('Subject ID required');
    }

    return view('lessonplan/form', [
        'units'        => $this->unitModel->findAll(),
        'subunits'     => $this->subunitModel->findAll(),
        'mainClass'    => $mainClass[0],

        'agama'        => $this->objectiveModel->getAgamaBySubject($subject_id),
        'jati'         => $this->objectiveModel->getJatiBySubject($subject_id),
        'literasi'     => $this->objectiveModel->getLiterasiBySubject($subject_id),
    ]);
}

    // GET /lessonplan/edit/{id}
    public function edit($id)
{
    $user_id = session('id') ?? session('user_id');

    $mainClass = $this->userModel->getUserMainClass($user_id);

    if (empty($mainClass)) {
        throw new \RuntimeException('User has no main class');
    }

    $grade = $mainClass[0]['grade'];

    $lessonplan = $this->lessonplan->find($id);

    if (!$lessonplan) {
        throw new \RuntimeException('Data tidak ditemukan');
    }

    return view('lessonplan/form', [
        'lessonplan'  => $lessonplan,
        'units'       => $this->unitModel->findAll(),
        'subunits'    => $this->subunitModel->findAll(),
        'mainClass'   => $mainClass[0],

        'agama'       => $this->objectiveModel->getAgamaBySubject($lessonplan['unit_id']),
        'jati'        => $this->objectiveModel->getJatiBySubject($lessonplan['unit_id']),
        'literasi'    => $this->objectiveModel->getLiterasiBySubject($lessonplan['unit_id']),
    ]);
}

    // POST /lessonplan/store
    public function store()
{
    // DPL checkbox → integer
    $dplArray = $this->request->getPost('dpl') ?? [];

    $dplValue = 0;
    foreach ($dplArray as $v) {
        $dplValue += (int)$v;
    }

    $data = [
        'class_id'   => $this->request->getPost('class_id'),
        'subject_id'   => $this->request->getPost('subject_id'),
        'unit_id'    => $this->request->getPost('unit_id'),
        'subunit_id' => $this->request->getPost('subunit_id'),
        'semester'   => $this->request->getPost('semester'),
        'bulan'      => $this->request->getPost('bulan'),
        'dpl'        => $dplValue,

        'agama1' => $this->request->getPost('agama1'),
        'agama2' => $this->request->getPost('agama2'),
        'jati1'  => $this->request->getPost('jati1'),
        'jati2'  => $this->request->getPost('jati2'),
        'dasar1' => $this->request->getPost('dasar1'),
        'dasar2' => $this->request->getPost('dasar2'),

        'iktp'       => $this->request->getPost('iktp'),
        'pedagogis'  => $this->request->getPost('pedagogis'),
        'kemitraan'  => $this->request->getPost('kemitraan'),
        'alatbahan'  => $this->request->getPost('alatbahan'),
        'sumber'     => $this->request->getPost('sumber'),

        'inti'    => $this->request->getPost('inti'),
        'penutup' => $this->request->getPost('penutup'),

        'sambut1' => $this->request->getPost('sambut1'),
        'sambut2' => $this->request->getPost('sambut2'),
        'sambut3' => $this->request->getPost('sambut3'),
        'sambut4' => $this->request->getPost('sambut4'),
        'sambut5' => $this->request->getPost('sambut5'),

        'pembukaan' => $this->request->getPost('pembukaan'),

        'inti1' => $this->request->getPost('inti1'),
        'inti2' => $this->request->getPost('inti2'),
        'inti3' => $this->request->getPost('inti3'),
        'inti4' => $this->request->getPost('inti4'),
        'inti5' => $this->request->getPost('inti5'),
    ];

    if (!$this->lessonplan->insert($data)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->lessonplan->errors());
    }

    return redirect()->to('/')->with('success', 'Data saved');
}

    // POST /lessonplan/update/{id}
    public function update($id)
{
    $dplArray = $this->request->getPost('dpl') ?? [];

    $dplValue = 0;
    foreach ($dplArray as $v) {
        $dplValue += (int)$v;
    }

    $data = [
        'class_id'    => $this->request->getPost('class_id'),
        'subject_id'    => $this->request->getPost('subject_id'),
        'unit_id'     => $this->request->getPost('unit_id'),
        'subunit_id'  => $this->request->getPost('subunit_id'),
        'semester'    => $this->request->getPost('semester'),
        'bulan'       => $this->request->getPost('bulan'),
        'dpl'         => $dplValue,

        'agama1'      => $this->request->getPost('agama1'),
        'agama2'      => $this->request->getPost('agama2'),

        'jati1'       => $this->request->getPost('jati1'),
        'jati2'       => $this->request->getPost('jati2'),

        'dasar1'      => $this->request->getPost('dasar1'),
        'dasar2'      => $this->request->getPost('dasar2'),

        'pedagogis'   => $this->request->getPost('pedagogis'),
        'kemitraan'   => $this->request->getPost('kemitraan'),
        'alatbahan'   => $this->request->getPost('alatbahan'),
        'sumber'      => $this->request->getPost('sumber'),

        'inti'        => $this->request->getPost('inti'),
        'penutup'     => $this->request->getPost('penutup'),
        'pembukaan'   => $this->request->getPost('pembukaan'),

        'sambut1'     => $this->request->getPost('sambut1'),
        'sambut2'     => $this->request->getPost('sambut2'),
        'sambut3'     => $this->request->getPost('sambut3'),
        'sambut4'     => $this->request->getPost('sambut4'),
        'sambut5'     => $this->request->getPost('sambut5'),

        'inti1'       => $this->request->getPost('inti1'),
        'inti2'       => $this->request->getPost('inti2'),
        'inti3'       => $this->request->getPost('inti3'),
        'inti4'       => $this->request->getPost('inti4'),
        'inti5'       => $this->request->getPost('inti5'),
    ];

    if (!$this->lessonplan->update($id, $data)) {
        return redirect()->back()
            ->withInput()
            ->with('errors', $this->lessonplan->errors());
    }

    return redirect()->to('/')->with('success', 'Data berhasil diupdate');
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

        return redirect()->to('/')
                         ->with('success', 'Deleted successfully');
    }
}