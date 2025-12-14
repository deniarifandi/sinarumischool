<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
            // return view('admin_base');
        return view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'name'  => session()->get('name'),
            'role'  => session()->get('role')
        ]);
    }
}
