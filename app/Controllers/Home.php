<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {   
        return view('dashboard.php');
    }
     public function blank()
    {   
        return view('blank.php');
    }
}
