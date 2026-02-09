<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DivisionModel;

class DivisionController extends BaseController
{
    protected $divisionModel;

    public function __construct()
    {
        $this->divisionModel = new DivisionModel();
    }

    public function index()
    {
        $data['division'] = $this->divisionModel->findAll();
        return view('division/index', $data);
    }

    public function create()
    {
        return view('division/form');
    }

    public function edit($id)
    {
        $data['division'] = $this->divisionModel->find($id);
        return view('division/form', $data);
    }

    public function save($id = null)
    {
        $data = [
            'division_name' => $this->request->getPost('division_name')
        ];

        if ($id) {
            $this->divisionModel->update($id, $data);
        } else {
            $this->divisionModel->insert($data);
        }

        return redirect()->to('/division');
    }

    public function delete($id)
    {
        $this->divisionModel->delete($id);
        return redirect()->to('/division');
    }
}
