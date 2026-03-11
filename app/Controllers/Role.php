<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RoleModel;

class Role extends BaseController
{
    protected $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data['roles'] = $this->roleModel->orderBy('id', 'DESC')->findAll();
        return view('roles/index', $data);
    }

    public function create()
    {
        return view('roles/form');
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);

        if (!$role) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('roles/form', ['role' => $role]);
    }

    public function save($id = null)
    {
        $rules = [
            'roles' => 'required|min_length[3]|is_unique[roles.roles,id,' . $id . ']',
            'description' => 'permit_empty|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'roles'       => $this->request->getPost('roles'),
            'description' => $this->request->getPost('description')
        ];

        if ($id) {
            $this->roleModel->update($id, $data);
        } else {
            $this->roleModel->insert($data);
        }

        return redirect()->to('/roles')
            ->with('success', 'Role saved successfully');
    }

    public function delete($id)
    {
        $role = $this->roleModel->find($id);

        if (!$role) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->roleModel->delete($id);

        return redirect()->to('/roles')
            ->with('success', 'Role deleted successfully');
    }
}