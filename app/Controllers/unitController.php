<?php

namespace App\Controllers;

use App\Libraries\datatable;
use Config\Database;

class unitController extends BaseController
{

    public function index(){
        return view('unit/list_unit.php');
    }

    public function new(){
        return view('unit/list_unit.php');
    }

}
