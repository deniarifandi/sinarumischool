<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
{
    if (!session()->get('logged_in')) {
        return redirect()->to(base_url('login'));
    }

    $userRole = strtolower(session()->get('role'));

    // If filter has 1 role (admin) or multiple roles (admin, guru)
    $allowedRoles = array_map('strtolower', $arguments);

    if (!in_array($userRole, $allowedRoles)) {
        return redirect()->to(base_url('no-access'));
    }
}


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
