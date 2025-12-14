<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class User extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $users = $db->table('users')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        return view('admin/users/index', ['users' => $users]);
    }

    public function create()
    {
        $db = \Config\Database::connect();
        $divisions = $db->table('divisions')->get()->getResultArray();

        return view('admin/users/create', ['divisions' => $divisions]);
    }

    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'name'  => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'  => $this->request->getPost('role'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
        ];

        // Insert into users table
        $db->table('users')->insert($data);
        $userId = $db->insertID();

        // Insert divisions
        $divisions = $this->request->getPost('divisions') ?? [];

        foreach ($divisions as $div) {
            $db->table('user_divisions')->insert([
                'user_id' => $userId,
                'division_id' => $div
            ]);
        }

        return redirect()->to('/admin/users');
    }

    public function edit($id)
    {
        $db = \Config\Database::connect();

        $user    = $db->table('users')->where('id', $id)->get()->getRowArray();
        $divs    = $db->table('divisions')->get()->getResultArray();

        $userDiv = $db->table('user_divisions')
                    ->where('user_id', $id)
                    ->get()->getResultArray();
        $userDiv = array_column($userDiv, 'division_id');

        return view('admin/users/edit', [
            'user' => $user,
            'divisions' => $divs,
            'userDivisions' => $userDiv
        ]);
    }

    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'name'  => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'role'  => $this->request->getPost('role'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $db->table('users')->where('id', $id)->update($data);

        // Reset & update divisions
        $db->table('user_divisions')->where('user_id', $id)->delete();

        $divisions = $this->request->getPost('divisions') ?? [];

        foreach ($divisions as $div) {
            $db->table('user_divisions')->insert([
                'user_id' => $id,
                'division_id' => $div
            ]);
        }

        return redirect()->to('/admin/users');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();

        $db->table('user_divisions')->where('user_id', $id)->delete();
        $db->table('users')->where('id', $id)->delete();

        return redirect()->to('/admin/users');
    }


    //datatable

    public function datatable()
    {
        $db = \Config\Database::connect();

        $builder = $db->table('users')
            ->select('id, username, name, nip, kkb, kkbnomor'); // NEVER select password

        return (new \App\Libraries\Datatable())->generate(
            $builder,
            'id',
            ['username', 'name', 'nip', 'kkb', 'kkbnomor'], // searchable
            ['username', 'name', 'nip', 'kkb', 'kkbnomor']  // orderable
        );
    }

}
