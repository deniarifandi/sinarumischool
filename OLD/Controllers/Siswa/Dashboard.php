<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('siswa/dashboard', [
            'title' => 'Siswa Dashboard',
            'name'  => session()->get('name'),
            'role'  => session()->get('role')
        ]);
    }
}
