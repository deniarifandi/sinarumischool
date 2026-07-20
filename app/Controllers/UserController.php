<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\DivisionModel;
use App\Models\UserDivisionModel;
use App\Models\RoleModel;
use App\Models\PositionModel;
use App\Models\UserPositionModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $divisionModel;
    protected $userDivisionModel;
    protected $roleModel;
    protected $positionModel;
    protected $userPositionModel;

    public function __construct()
    {
           $this->userModel         = new UserModel();
            $this->divisionModel     = new DivisionModel();
            $this->userDivisionModel = new UserDivisionModel();
            $this->roleModel         = new RoleModel();
            $this->positionModel     = new PositionModel();
            $this->userPositionModel = new UserPositionModel();
    }

    /* =========================
       USER LIST
    ========================== */
   public function index()
{
    $user_id = session('id') ?? session('user_id');
    $userDetail = $this->userModel->getUserDetailData($user_id);

    $rows = $this->userModel->getUsersData();

    $users = [];

    foreach ($rows as $r) {
        $uid = $r['id'];

        if (!isset($users[$uid])) {
            $users[$uid] = [
                'id'           => $r['id'],
                'name'         => $r['name'],
                'username'     => $r['username'],
                'role'         => $r['role'],
                'divisions'    => [],
                'division_ids' => [],
                'positions'    => [],
                'position_ids' => [],
            ];
        }

        if (!empty($r['division_id'])) {
            $users[$uid]['division_ids'][] = (int) $r['division_id'];
            $users[$uid]['divisions'][]    = $r['division_name'];
        }

        if (!empty($r['jabatan_id'])) {
            $users[$uid]['position_ids'][] = (int) $r['jabatan_id'];
            $users[$uid]['positions'][]    = $r['jabatan_nama'];
        }
    }

    return view('users/index', [
        'users'      => array_values($users),
        'divisions'  => $this->divisionModel->findAll(),
        'positions'  => $this->positionModel->findAll(),
        'roles'      => $this->roleModel->findAll(),
        'user_detail'=> $userDetail[0],
    ]);
}

/* =========================
   POSITION / JABATAN (MODAL)
========================== */
public function updatePosition($userId)
{
    $positions = $this->request->getPost('jabatan') ?? [];

    // reset
    $this->userPositionModel
         ->where('guru_id', $userId)
         ->delete();

    // insert new
    foreach ($positions as $jabatanId) {
        $this->userPositionModel->insert([
            'guru_id'    => $userId,
            'jabatan_id' => $jabatanId,
        ]);
    }

    return redirect()->to('/users')->with('success', 'Positions updated');
}

public function dashboard()
{
    $user_id    = session('id') ?? session('user_id');
    $userDetail = $this->userModel->getUserDetailData($user_id);
    $rows       = $this->userModel->getUsersForDashboard();

    // Optional division filter, e.g. /dashboard?division=3
    $filterDivision = $this->request->getGet('division');
    $filterDivision = $filterDivision !== null && $filterDivision !== '' ? (int) $filterDivision : null;

    // --- Group raw rows into one entry per user ---
    $users = [];
    foreach ($rows as $r) {
        $uid = $r['id'];

        if (!isset($users[$uid])) {
            $users[$uid] = [
                'id'           => $r['id'],
                'name'         => $r['name'],
                'username'     => $r['username'],
                'role'         => $r['role'],
                'kkb'          => $r['kkb'],
                'kkbnomor'     => $r['kkbnomor'],
                'kkbstart'     => $r['kkbstart'],
                'division_ids' => [],
                'divisions'    => [],
            ];
        }

        if (!empty($r['division_id'])) {
            $users[$uid]['division_ids'][] = (int) $r['division_id'];
            $users[$uid]['divisions'][]    = $r['division_name'];
        }
    }

    $users = array_values($users);

    // --- KKB duration breakdown ---
    $kkbDurationCounts = [];
    $noKkbCount        = 0;

    foreach ($users as $u) {
        if (empty($u['kkb'])) {
            $label = 'No KKB Data';
            $noKkbCount++;
        } else {
            $label = (int) $u['kkb'] . ' Tahun';
        }

        $kkbDurationCounts[$label] = ($kkbDurationCounts[$label] ?? 0) + 1;
    }

    arsort($kkbDurationCounts);

    // --- Division breakdown ---
    $allDivisions = $this->divisionModel->findAll();

    $divisionCounts = [];
    foreach ($allDivisions as $d) {
        $divisionCounts[$d['id']] = [
            'name'  => $d['division_name'],
            'count' => 0,
        ];
    }

    $unassignedDivisionCount = 0;

    foreach ($users as $u) {
        if (empty($u['division_ids'])) {
            $unassignedDivisionCount++;
            continue;
        }

        foreach ($u['division_ids'] as $did) {
            if (isset($divisionCounts[$did])) {
                $divisionCounts[$did]['count']++;
            }
        }
    }

    uasort($divisionCounts, fn($a, $b) => $b['count'] <=> $a['count']);

    // --- KKB renewal calculation ---
    $urgentDays  = 30;
    $warningDays = 90;
    $today       = new \DateTime('today');

    $kkbList = [];

    foreach ($users as $u) {

        // Apply division filter
        if ($filterDivision !== null && !in_array($filterDivision, $u['division_ids'], true)) {
            continue;
        }

        // No KKB data
        if (empty($u['kkb']) || empty($u['kkbstart'])) {
            $kkbList[] = [
                'id'         => $u['id'],
                'name'       => $u['name'],
                'username'   => $u['username'],
                'divisions'  => $u['divisions'],
                'kkbnomor'   => $u['kkbnomor'],
                'kkb_years'  => null,
                'kkbstart'   => null,
                'expiry'     => null,
                'days_left'  => PHP_INT_MAX,
                'status'     => 'no_kkb',
            ];
            continue;
        }

        $start = \DateTime::createFromFormat('Y-m-d', $u['kkbstart']);

        // Invalid date
        if (!$start) {
            $kkbList[] = [
                'id'         => $u['id'],
                'name'       => $u['name'],
                'username'   => $u['username'],
                'divisions'  => $u['divisions'],
                'kkbnomor'   => $u['kkbnomor'],
                'kkb_years'  => $u['kkb'],
                'kkbstart'   => $u['kkbstart'],
                'expiry'     => null,
                'days_left'  => PHP_INT_MAX,
                'status'     => 'no_kkb',
            ];
            continue;
        }

        $expiry = clone $start;
        $expiry->modify('+' . (int) $u['kkb'] . ' years');

        $daysLeft = (int) $today->diff($expiry)->format('%r%a');

        if ($daysLeft < 0) {
            $status = 'expired';
        } elseif ($daysLeft <= $urgentDays) {
            $status = 'urgent';
        } elseif ($daysLeft <= $warningDays) {
            $status = 'warning';
        } else {
            $status = 'ok';
        }

        $kkbList[] = [
            'id'         => $u['id'],
            'name'       => $u['name'],
            'username'   => $u['username'],
            'divisions'  => $u['divisions'],
            'kkbnomor'   => $u['kkbnomor'],
            'kkb_years'  => $u['kkb'],
            'kkbstart'   => $u['kkbstart'],
            'expiry'     => $expiry->format('Y-m-d'),
            'days_left'  => $daysLeft,
            'status'     => $status,
        ];
    }

    usort($kkbList, function ($a, $b) {
        return $a['days_left'] <=> $b['days_left'];
    });

    $kkbNeedsRenewal = array_values(array_filter(
        $kkbList,
        fn($k) => in_array($k['status'], ['expired', 'urgent', 'warning'])
    ));

    $kkbAll = $kkbList;


    // --- ADD THIS LINE FOR POSITIONS ---
    // If you haven't loaded the model yet, use: $positionModel = new \App\Models\PositionModel();
    // Assuming it's already instantiated as $this->positionModel:
    $totalPositionsCount = $this->positionModel->countAllResults();

    return view('users/dashboard', [
        'user_detail'             => $userDetail[0],
        'totalStaff'              => count($users),
        'totalDivisions'          => count($allDivisions),
        'kkbDurationCounts'       => $kkbDurationCounts,
        'totalPositions'          => $totalPositionsCount,
        'noKkbCount'              => $noKkbCount,
        'divisionCounts'          => $divisionCounts,
        'unassignedDivisionCount' => $unassignedDivisionCount,
        'allDivisionsForFilter'   => $allDivisions,
        'selectedDivision'        => $filterDivision,
        'kkbNeedsRenewal'         => $kkbNeedsRenewal,
        'kkbAll'                  => $kkbAll,
    ]);
}

   public function dashboardold()
{
    $user_id     = session('id') ?? session('user_id');
    $userDetail  = $this->userModel->getUserDetailData($user_id);
    $rows        = $this->userModel->getUsersForDashboard();

    // Optional division filter, e.g. /dashboard?division=3
    $filterDivision = $this->request->getGet('division');
    $filterDivision = $filterDivision !== null && $filterDivision !== '' ? (int) $filterDivision : null;

    // --- group raw rows into one entry per user (a user can belong to multiple divisions) ---
    $users = [];
    foreach ($rows as $r) {
        $uid = $r['id'];
        if (!isset($users[$uid])) {
            $users[$uid] = [
                'id'           => $r['id'],
                'name'         => $r['name'],
                'username'     => $r['username'],
                'role'         => $r['role'],
                'kkb'          => $r['kkb'],
                'kkbnomor'     => $r['kkbnomor'],
                'kkbstart'     => $r['kkbstart'],
                'division_ids' => [],
                'divisions'    => [],
            ];
        }
        if (!empty($r['division_id'])) {
            $users[$uid]['division_ids'][] = (int) $r['division_id'];
            $users[$uid]['divisions'][]    = $r['division_name'];
        }
    }
    $users = array_values($users);

    // --- KKB duration breakdown (all staff, unfiltered) ---
    $kkbDurationCounts = [];
    $noKkbCount        = 0;
    foreach ($users as $u) {
        if ($u['kkb'] === null || $u['kkb'] === '') {
            $noKkbCount++;
            $label = 'No KKB Data';
        } else {
            $label = (int) $u['kkb'] . ' Tahun';
        }
        $kkbDurationCounts[$label] = ($kkbDurationCounts[$label] ?? 0) + 1;
    }
    
    // Sorts duration categories from highest count to lowest count
    arsort($kkbDurationCounts);

    // --- division breakdown (all staff, unfiltered) ---
    $allDivisions   = $this->divisionModel->findAll();
    $divisionCounts = [];
    foreach ($allDivisions as $d) {
        $divisionCounts[$d['id']] = [
            'name'  => $d['division_name'],
            'count' => 0,
        ];
    }
    $unassignedDivisionCount = 0;
    foreach ($users as $u) {
        if (empty($u['division_ids'])) {
            $unassignedDivisionCount++;
            continue;
        }
        foreach ($u['division_ids'] as $did) {
            if (isset($divisionCounts[$did])) {
                $divisionCounts[$did]['count']++;
            }
        }
    }

    // Sorts division lists from highest staff count to lowest staff count
    uasort($divisionCounts, fn($a, $b) => $b['count'] <=> $a['count']);

    // --- KKB renewal calculation ---
    $urgentDays  = 30;
    $warningDays = 90;
    $today       = new \DateTime('today');
    $kkbList     = [];

    foreach ($users as $u) {
        // Apply division filter here: skip users not in the selected division
        if ($filterDivision !== null && !in_array($filterDivision, $u['division_ids'], true)) {
            continue;
        }

        if (empty($u['kkbstart']) || empty($u['kkb'])) {
            continue;
        }

        $start = \DateTime::createFromFormat('Y-m-d', $u['kkbstart']);
        if (!$start) {
            continue;
        }

        $expiry   = clone $start;
        $expiry->modify('+' . (int) $u['kkb'] . ' years');
        $daysLeft = (int) $today->diff($expiry)->format('%r%a'); // signed

        if ($daysLeft < 0) {
            $status = 'expired';
        } elseif ($daysLeft <= $urgentDays) {
            $status = 'urgent';
        } elseif ($daysLeft <= $warningDays) {
            $status = 'warning';
        } else {
            $status = 'ok';
        }

        $kkbList[] = [
            'id'         => $u['id'],
            'name'       => $u['name'],
            'username'   => $u['username'],
            'divisions'  => $u['divisions'],
            'kkbnomor'   => $u['kkbnomor'],
            'kkb_years'  => $u['kkb'],
            'kkbstart'   => $u['kkbstart'],
            'expiry'     => $expiry->format('Y-m-d'),
            'days_left'  => $daysLeft,
            'status'     => $status,
        ];
    }

    usort($kkbList, fn($a, $b) => $a['days_left'] <=> $b['days_left']);

    $kkbNeedsRenewal = array_values(array_filter(
        $kkbList,
        fn($k) => in_array($k['status'], ['expired', 'urgent', 'warning'])
    ));

    $kkbAll = array_values($kkbList);

    return view('users/dashboard', [
        'user_detail'              => $userDetail[0],
        'totalStaff'               => count($users),
        'totalDivisions'           => count($allDivisions),
        'kkbDurationCounts'        => $kkbDurationCounts,
        'noKkbCount'               => $noKkbCount,
        'divisionCounts'           => $divisionCounts,
        'unassignedDivisionCount'  => $unassignedDivisionCount,
        'allDivisionsForFilter'    => $allDivisions,
        'selectedDivision'         => $filterDivision,
        'kkbAll'                   => $kkbList,
        'kkbNeedsRenewal'          => $kkbNeedsRenewal,
        'kkbAll'                   => $kkbAll
    ]);
}



    public function getUsersForDashboard()
    {
        return $this->select('id, name, username, role, kkb, kkbnomor, kkbstart')
                    ->where('deleted_at', null)
                    ->findAll();
    }

    /* =========================
       CREATE / EDIT USER
    ========================== */
    public function create()
    {
        return view('users/form');
    }

    public function edit($id)
    {
        return view('users/form', [
            'user' => $this->userModel->find($id),
        ]);
    }

    public function save($id = null)
    {
        $rules = [
            'name'     => 'required|min_length[3]',
            'username' => $id ? 'permit_empty' : 'required|is_unique[users.username]',
            'password' => $id ? 'permit_empty' : 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = $this->request->getPost([
            'name','username','gender','nip','nik','placebirth',
            'datebirth','religion','marital','phone','bca',
            'address','bpjskesehatan','bpjsketenagakerjaan',
            'kkb','kkbstart','kkbnomor'
        ]);

        if ($password = $this->request->getPost('password')) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $id
            ? $this->userModel->update($id, $data)
            : $this->userModel->insert($data);

        return redirect()->to('/users')->with('success', 'User saved');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('/users')->with('success', 'User deleted');
    }

    /* =========================
       ROLE (MODAL)
    ========================== */
    public function updateRole($id)
    {
        $this->userModel->update($id, [
            'role' => $this->request->getPost('role')
        ]);

        return redirect()->to('/users')->with('success', 'Role updated');
    }

    /* =========================
       DIVISION (MODAL)
    ========================== */
    public function updateDivision($userId)
    {
        $divisions = $this->request->getPost('divisi') ?? [];

        // reset
        $this->userDivisionModel
             ->where('user_id', $userId)
             ->delete();

        // insert new
        foreach ($divisions as $divisionId) {
            $this->userDivisionModel->insert([
                'user_id'     => $userId,
                'division_id' => $divisionId,
            ]);
        }

        return redirect()->to('/users')->with('success', 'Divisions updated');
    }
}
