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

        if ($this->request->getPost('remember')) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+30 days'));

            db_connect()->table('user_remember_tokens')->insert([
                'user_id' => $user['id'],
                'token' => hash('sha256', $token),
                'expires_at' => $expires,
            ]);

            setcookie(
                'remember_token',
                $token,
                time() + (60*60*24*30),
                '/',
                '',
                false,
                true // httpOnly
            );
        }


        session()->set([
            'id'        => $user['id'],
            // 'name'      => $user['name'],
            // 'username'  => $user['username'],
            'logged_in' => true
        ]);

        return redirect()->to('/');
    }



    public function logout()
    {
        if (isset($_COOKIE['remember_token'])) {
            db_connect()->table('user_remember_tokens')
                ->where('token', hash('sha256', $_COOKIE['remember_token']))
                ->delete();

            setcookie('remember_token', '', time()-3600, '/');
        }

        session()->destroy();
        return redirect()->to('/login');
    }
}
