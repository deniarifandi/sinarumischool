<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DivisionModel;
use App\Models\UserDivisionModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $divisionModel;
    protected $userDivisionModel;

    public function __construct()
    {
        $this->userModel         = new UserModel();
        $this->divisionModel     = new DivisionModel();
        $this->userDivisionModel = new UserDivisionModel();
    }

    /* =========================
       USER LIST
    ========================== */
    public function index()
    {
        $rows = $this->userModel->getUsersData();

        $users = [];

        foreach ($rows as $r) {
            $uid = $r['id'];

            if (!isset($users[$uid])) {
                $users[$uid] = [
                    'id'           => $r['id'],
                    'name'         => $r['name'],
                    'username'     => $r['username'],
                    'role'         => $r['role'],
                    'divisions'    => [],
                    'division_ids' => [],
                ];
            }

            if (!empty($r['division_id'])) {
                $users[$uid]['division_ids'][] = (int) $r['division_id'];
                $users[$uid]['divisions'][]    = $r['division_name'];
            }
        }

        return view('users/index', [
            'users'     => array_values($users),
            'divisions' => $this->divisionModel->findAll(),
        ]);
    }

    /* =========================
       CREATE / EDIT USER
    ========================== */
    public function create()
    {
        return view('users/form');
    }

    public function edit($id)
    {
        return view('users/form', [
            'user' => $this->userModel->find($id),
        ]);
    }

    public function save($id = null)
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'username' => $id ? 'permit_empty' : 'required|is_unique[users.username]',
            'password' => $id ? 'permit_empty' : 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = $this->request->getPost([
            'name','username','gender','nip','nik','placebirth',
            'datebirth','religion','marital','phone','bca',
            'address','bpjskesehatan','bpjsketenagakerjaan',
            'kkb','kkbstart','kkbnomor'
        ]);

        if ($password = $this->request->getPost('password')) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $id
            ? $this->userModel->update($id, $data)
            : $this->userModel->insert($data);

        return redirect()->to('/users')->with('success', 'User saved');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted');
    }

    /* =========================
       ROLE (MODAL)
    ========================== */
    public function updateRole($id)
    {
        $this->userModel->update($id, [
            'role' => $this->request->getPost('role')
        ]);

        return redirect()->to('/users')->with('success', 'Role updated');
    }

    /* =========================
       DIVISION (MODAL)
    ========================== */
    public function updateDivision($userId)
    {
        $divisions = $this->request->getPost('divisi') ?? [];

        // reset
        $this->userDivisionModel
             ->where('user_id', $userId)
             ->delete();

        // insert new
        foreach ($divisions as $divisionId) {
            $this->userDivisionModel->insert([
                'user_id'     => $userId,
                'division_id' => $divisionId,
            ]);
        }

        return redirect()->to('/users')->with('success', 'Divisions updated');
    }
}
