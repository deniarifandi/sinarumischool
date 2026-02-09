<?php

namespace App\Controllers;

use App\Models\PresenceModel;
use App\Models\UserDivisionModel;

class Home extends BaseController
{

    public function __construct()
    {    
        $this->presence = new PresenceModel();
        $this->userdivision = new UserDivisionModel();

    }

    public function index(): string
    {   
        $guruId = session('user_id'); // sesuaikan dengan session kamu
        $checkedToday = $this->presence->presence_check(session('id'));

        // ambil divisi user
        $divisions = $this->userdivision->getUserDivisions(session('id'));

        // print_r($divisions);
        // exit();
        return view('dashboard', [
            'checkedToday' => $checkedToday,
            'divisions'    => $divisions
        ]);
    }
   
 }
