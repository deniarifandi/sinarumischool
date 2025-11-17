<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $db = \Config\Database::connect();
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Ambil user
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        $divisions = $db->table('user_divisions')
        ->select('division_id')
        ->where('user_id', $user['id'])
        ->get()
        ->getResultArray();

        $userDivisions = array_column($divisions, 'division_id');

        if (!$user) {
            $session->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->to('/login');
        }

        // Verifikasi password
        if (!password_verify($password, $user['password'])) {
            $session->setFlashdata('error', 'Password salah');
            return redirect()->to('/login');
        }

        // Set session multirole
        $session->set([
            'id'        => $user['id'],
            'name'      => $user['name'],
            'username'  => $user['username'],
            'role'      => $user['role'], // admin / guru / siswa
            'logged_in' => true,
            'divisions'     => $userDivisions
        ]);

        // Redirect sesuai role
        return redirect()->to(base_url('/'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
