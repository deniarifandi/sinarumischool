<?php

namespace App\Controllers;

class Profile extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $user = $db->table('users')
            ->where('id', session('id') ?? session('user_id'))
            ->get()
            ->getRowArray();

        return view('personal/profile', ['user' => $user]);
    }

    public function form()
    {
        $db = \Config\Database::connect();
        $id = session()->get('id');

        $user = $db->table('users')
            ->where('id', $id)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->to(base_url('profile'));
        }

        return view('personal/form', [
            'user' => $user
        ]);
    }


    public function update()
    {
        $db = \Config\Database::connect();
        $id = session()->get('id');

        $data = [
            'name'        => $this->request->getPost('name'),
            'nip'         => $this->request->getPost('nip'),
            'nik'         => $this->request->getPost('nik'),
            'gender'      => $this->request->getPost('gender'),
            'religion'    => $this->request->getPost('religion'),
            'placebirth'  => $this->request->getPost('placebirth'),
            'datebirth'   => $this->request->getPost('datebirth'),
            'phone'       => $this->request->getPost('phone'),
            'address'     => $this->request->getPost('address'),
            'bca'         => $this->request->getPost('bca'),
            'kkb'         => $this->request->getPost('kkb'),
            'marital'         => $this->request->getPost('marital'),
            'kkbstart'         => $this->request->getPost('kkbstart'),
            'kkbnomor'         => $this->request->getPost('kkbnomor'),
            'bpjskesehatan'         => $this->request->getPost('bpjskesehatan'),
            'bpjsketenagakerjaan'         => $this->request->getPost('bpjsketenagakerjaan'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        // upload handler
        $files = [
            'pasfoto' => 'uploads/avatar',
            'filektp' => 'uploads/docs',
            'filekk'  => 'uploads/docs',
            'arsip'   => 'uploads/arsip',
        ];

        foreach ($files as $field => $path) {
            $file = $this->request->getFile($field);
            if ($file && $file->isValid()) {
                $newName = $file->getRandomName();
                $file->move($path, $newName);
                $data[$field] = $path.'/'.$newName;
            }
        }

        $db->table('users')->where('id', $id)->update($data);

        return redirect()->to(base_url('profile'))
            ->with('success', 'Profile updated');
    }

    public function security()
    {
        return view('personal/security');
    }

    public function changePassword()
{
    $db = \Config\Database::connect();
    $id = session()->get('id');

    $current = $this->request->getPost('current_password');
    $new     = $this->request->getPost('new_password');
    $confirm = $this->request->getPost('confirm_password');

    if ($new !== $confirm) {
        return redirect()->back()->with('error', 'Password confirmation mismatch');
    }

    $user = $db->table('users')->where('id', $id)->get()->getRowArray();
    if (!$user || !password_verify($current, $user['password'])) {
        return redirect()->back()->with('error', 'Current password invalid');
    }

    $db->table('users')
        ->where('id', $id)
        ->update([
            'password'   => password_hash($new, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    return redirect()->to(base_url('profile'))
        ->with('success', 'Password updated');
}


   
 }
