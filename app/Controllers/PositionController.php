<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PositionModel;
use App\Models\UserModel;

class PositionController extends BaseController
{
    protected $positionModel;
    protected $userModel;

    public function __construct()
    {
        $this->positionModel = new PositionModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        $user_id    = session('id') ?? session('user_id');
        $userDetail = $this->userModel->getUserDetailData($user_id);

        return view('positions/index', [
            'positions'   => $this->positionModel->orderBy('jabatan_nama', 'ASC')->findAll(),
            'user_detail' => $userDetail[0],
        ]);
    }

    public function save($id = null)
    {
        $rules = [
            'jabatan_nama' => 'required|min_length[2]|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', $this->validator->listErrors());
        }

        $data = [
            'jabatan_nama' => $this->request->getPost('jabatan_nama'),
        ];

        if ($id) {
            $this->positionModel->update($id, $data);
            $message = 'Position updated';
        } else {
            $this->positionModel->insert($data);
            $message = 'Position added';
        }

        return redirect()->to('/positions')->with('success', $message);
    }

    public function delete($id)
    {
        $this->positionModel->delete($id);
        return redirect()->to('/positions')->with('success', 'Position deleted');
    }
}