<?php

namespace App\Controllers;

use App\Models\PresenceModel;

class Home extends BaseController
{

    public function __construct()
    {    
        $this->presence = new PresenceModel();
    }

    public function index(): string
    {   
        $checkedToday = $this->presence->presence_check(session('id'));
        // print_r($checkedToday);
        // exit();
        return view('dashboard',['checkedToday' => $checkedToday]);
    }
   
 }
