<?php

namespace App\Controllers;

use App\Models\PresenceModel;
use App\Models\UserDivisionModel;
use App\Models\UserModel;

class Home extends BaseController
{

    public function __construct()
    {    
        $this->presence = new PresenceModel();
        $this->userDivision = new UserDivisionModel();
        $this->userModel = new UserModel();

    }

    public function index(): string
    {   

    
        $user_id= session('id'); // sesuaikan dengan session kamu
        $checkedToday = $this->presence->presence_check($user_id);

        // ambil divisi user
        $divisions = $this->userDivision->getUserDivisions($user_id);

        $userDetail = $this->userModel->getUserDetailData($user_id);

        $mainClassId = $this->userModel->getUserMainClass($user_id);

        print_r(session()->get());
        print_r($userDetail);
        exit();
        return view('dashboard', [
            'checkedToday' => $checkedToday,
            'divisions'    => $divisions,
            'user'         => $userDetail[0]
        ]);
    }
   
 }
