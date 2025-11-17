<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        return view('guru/dashboard', [
            'title' => 'Guru Dashboard',
            'name'  => session()->get('name'),
            'role'  => session()->get('role')
        ]);
    }
}
