<?php

namespace App\Controllers;

use App\Models\PresenceModel;
use App\Models\UserDivisionModel;
use App\Models\UserModel;
use App\Models\UserSubjectModel;

class Home extends BaseController
{

    public function __construct()
    {    
        $this->presence = new PresenceModel();
        $this->userDivision = new UserDivisionModel();
        $this->userModel = new UserModel();
        $this->UserSubjectModel = new UserSubjectModel();

    }

    public function index(): string
    {   

        $user_id = session('id') ?? session('user_id');
        $checkedToday = $this->presence->presence_check($user_id);

        // ambil divisi user
        $divisions = $this->userDivision->getUserDivisions($user_id);

        $userDetail = $this->userModel->getUserDetailData($user_id);

        $mainClass = $this->userModel->getUserMainClass($user_id);

        $userSubjects = $this->UserSubjectModel->getUserSubjects($user_id);

        // print_r(session()->get());
        // print_r($userSubjects);
        // echo json_encode($userSubjects);
        // exit();
        $allowedRoles = ['superadmin', 'teacher', 'teacher_admin'];

        $groupedSubjects = [];

        if (in_array($userDetail[0]['role'], $allowedRoles) && !empty($userSubjects)) {
            foreach ($userSubjects as $subject) {
                $groupedSubjects[$subject['division_name']][] = $subject;
            }
        }
        return view('dashboard', [
            'checkedToday' => $checkedToday,
            'divisions'    => $divisions,
            'user'         => $userDetail[0],
            'mainClass'    => $mainClass[0],
            'userSubjects' => $userSubjects,
            'allowedRoles'  => $allowedRoles,
            'groupedSubjects'   => $groupedSubjects

        ]);
    }
   
 }
