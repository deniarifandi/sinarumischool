<?php

namespace App\Controllers;

use App\Models\RekapModel;
use App\Models\UserModel;
use App\Models\DivisionModel;

class RekapController extends BaseController
{
    protected RekapModel $rekapModel;

    public function __construct()
    {
        $this->rekapModel = new RekapModel();
        $this->userModel = new userModel();
        $this->divisionsModel = new DivisionModel();
    }

    public function index()
    {
        $rekaps = $this->rekapModel
        ->select('setting_rekap_501.*, divisions.division_name, users.name')
        ->join('divisions', 'divisions.id = setting_rekap_501.division_id', 'left')
        ->join('users', 'users.id = setting_rekap_501.user_id', 'left')
        ->findAll();

        $users = $this->userModel
    ->orderBy('name', 'ASC')
    ->findAll();

     $divisions = $this->divisionsModel
            ->select('id, division_name')
            ->orderBy('id', 'ASC')
            ->findAll();

        return view('rekap/index', [
            'rekaps' => $rekaps,
            'users' => $users,
            'divisions' => $divisions
        ]);
    }

      public function create()
        {
            $rekaps = $this->rekapModel->findAll(); // get all records
               $users = $this->userModel
            ->orderBy('name', 'ASC')
            ->findAll();
            $divisions = $this->divisionsModel
            ->select('id, division_name')
            ->orderBy('id', 'ASC')
            ->findAll();


            return view('rekap/form', [
                'rekaps' => $rekaps,
                'users' => $users,
                'divisions' => $divisions
            ]);
        }

        public function edit($id)
        {
            $rekap = $this->rekapModel->find($id);
               $users = $this->userModel
            ->orderBy('name', 'ASC')
            ->findAll();
             $divisions = $this->divisionsModel
            ->select('id, division_name')
            ->orderBy('id', 'ASC')
            ->findAll();


            return view('rekap/form', [
                'rekap' => $rekap,
                'users' => $users,
                'divisions' => $divisions
            ]);
        }

    public function save($id = null)
    {
        $data = [
            'division_id' => $this->request->getPost('division_id'),
            'user_group'  => $this->request->getPost('user_group'),
            'group_sort'  => $this->request->getPost('group_sort'),
            'user_role'   => $this->request->getPost('user_role'),
            'role_sort'   => $this->request->getPost('role_sort'),
            'user_id'     => $this->request->getPost('user_id'),
            'nullified'   => $this->request->getPost('nullified'),
        ];

        if ($id) {
            $this->rekapModel->update($id, $data);
        } else {
            $this->rekapModel->insert($data);
        }

        return redirect()->to('/rekap');
    }

public function print()
{
    $divisionId = $this->request->getGet('division_id');
    $datestart  = $this->request->getGet('date_start');
    $dateend    = $this->request->getGet('date_end');

    $db = \Config\Database::connect();

    // presence subquery
    $presenceSub = $db->table('presensidata')
        ->select('guru_id, COUNT(*) as total_presence')
        ->where('presensidata_tanggal >=', $datestart)
        ->where('presensidata_tanggal <=', $dateend)
        ->groupBy('guru_id')
        ->getCompiledSelect();

    $builder = $db->table('setting_rekap_501 r');

    $rows = $builder
        ->select('
            r.user_group,
            r.group_sort,
            r.user_role,
            r.role_sort,
            r.user_id,
            r.nullified,
            u.name as user_name,
            d.division_name,
            COALESCE(p.total_presence,0) as total_presence
        ')
        ->join('users u','u.id = r.user_id','left')
        ->join('divisions d','d.id = r.division_id','left')
        ->join("($presenceSub) p",'p.guru_id = r.user_id','left')
        ->where('r.division_id',$divisionId)
        ->orderBy('r.group_sort','ASC')
        ->orderBy('r.role_sort','ASC')
        ->get()
        ->getResultArray();

        // print_r($rows);
        // exit();

    // group result
    $rekaps = [];

    foreach ($rows as $row) {

        if (!isset($rekaps[$row['user_group']])) {
            $rekaps[$row['user_group']] = [
                'group' => $row['user_group'],
                'users' => []
            ];
        }

        $rekaps[$row['user_group']]['users'][] = $row;
    }

    $data['rekaps'] = array_values($rekaps);
    $data['date_start'] = $datestart;
    $data['date_end']   = $dateend;

    return view('rekap/print', $data);
}


public function print3()
{
    $divisionId = $this->request->getGet('division_id');
    $datestart = $this->request->getGet('date_start');
    $dateend = $this->request->getGet('date_end');

    $db = \Config\Database::connect();

    // 1️⃣ get all groups
    $groupBuilder = $db->table('setting_rekap_501');
    $groups = $groupBuilder
        ->select('user_group, group_sort')
        ->where('division_id', $divisionId)
        ->groupBy('user_group')
        ->orderBy('group_sort', 'ASC')
        ->get()
        ->getResultArray();

    $rekaps = [];

    // 2️⃣ loop groups
    foreach ($groups as $g) {

        $userBuilder = $db->table('setting_rekap_501 r');

        $users = $userBuilder
            ->select('
                r.user_group,
                r.user_role,
                r.role_sort,
                u.name as user_name,
                d.division_name
            ')
            ->join('users u', 'u.id = r.user_id', 'left')
            ->join('divisions d', 'd.id = r.division_id', 'left')
            ->where('r.division_id', $divisionId)
            ->where('r.user_group', $g['user_group'])
            ->orderBy('r.role_sort', 'ASC')
            ->get()
            ->getResultArray();

        $rekaps[] = [
            'group' => $g['user_group'],
            'users' => $users
        ];
    }

    $data['rekaps'] = $rekaps;
    $data['start'] = $datestart;
    $data['end'] = $dateend;

    return view('rekap/print', $data);
}

  public function print2()
{
    $divisionId = $this->request->getGet('division_id');
    $start      = $this->request->getGet('date_start');
    $end        = $this->request->getGet('date_end');

    $db = \Config\Database::connect();

    $builder = $db->table('setting_rekap_501 r');

    $builder->select('
        r.id,
        r.division_id,
        r.user_group,
        r.group_sort,
        r.user_role,
        r.role_sort,
        r.user_id,
        d.division_name,
        u.name AS user_name
    ');

    $builder->join('divisions d', 'd.id = r.division_id', 'left');
    $builder->join('users u', 'u.id = r.user_id', 'left');

    // filter division
    if (!empty($divisionId)) {
        $builder->where('r.division_id', $divisionId);
    }

    // sorting (important for grouping)
    $builder->orderBy('r.group_sort', 'ASC');
    $builder->orderBy('r.user_group', 'ASC');
    $builder->orderBy('r.role_sort', 'ASC');

    $data['rekaps'] = $builder->get()->getResultArray();

    // send filters to view (for header / print info)
    $data['divisionId'] = $divisionId;
    $data['date_start'] = $start;
    $data['date_end']   = $end;

    return view('rekap/print', $data);
}
}