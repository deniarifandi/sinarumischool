<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CurriculumFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $role = session()->get('role'); // sesuaikan dengan nama session key role kamu

        // if ($role !== 'teacher_admin') {
        //     return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu
    }
}