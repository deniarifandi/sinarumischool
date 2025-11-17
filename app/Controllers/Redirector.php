<?php

namespace App\Controllers;

class Redirector extends BaseController
{
    public function index()
    {
        // 1. Belum login
        if (!session()->get('logged_in')) {
            return redirect()->to(base_url('login'));
        }

        // 2. Belum memilih division
        // if (!session()->get('active_division')) {
        //     return redirect()->to(base_url('select-division'));
        // }

        // 3. Sudah login + sudah pilih division → redirect ke dashboard role
        $role = strtolower(session()->get('role'));

        switch ($role) {
            case 'admin':
                return redirect()->to(base_url('admin/dashboard'));
            case 'guru':
                return redirect()->to(base_url('guru/dashboard'));
            case 'siswa':
                return redirect()->to(base_url('siswa/dashboard'));
            case 'operator':
                return redirect()->to(base_url('operator/dashboard'));
            default:
                return redirect()->to(base_url('login'));
        }
    }
}
