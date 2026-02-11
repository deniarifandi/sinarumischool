<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            $token = $_COOKIE['remember_token'] ?? null;

            if ($token) {
                $row = db_connect()->table('user_remember_tokens')
                    ->where('token', hash('sha256', $token))
                    ->where('expires_at >=', date('Y-m-d H:i:s'))
                    ->get()
                    ->getRow();

                if ($row) {
                    $session->set([
                        'user_id'   => $row->user_id,
                        'logged_in'=> true,
                    ]);
                    return; // stop here, user now logged in
                }
            }

            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
