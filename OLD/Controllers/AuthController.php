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
            'logged_in' => true
        ]);

        // Redirect sesuai role
        switch ($user['role']) {
            case '1':
                echo "admin";
                return redirect()->to('/admin/dashboard');
            case '2':
                return redirect()->to('/guru/dashboard');
            case '3':
                return redirect()->to('/siswa/dashboard');
            default:
                return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
