<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function loginAuth()
    {
        $db = \Config\Database::connect();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $db->table('users')
            ->where('username', $username)
            ->get()
            ->getRowArray();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Wrong password');
        }

        session()->set([
            'id'        => $user['id'],
            'name'      => $user['name'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
