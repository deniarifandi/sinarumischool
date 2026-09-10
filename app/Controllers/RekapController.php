<?php

namespace App\Controllers;

use App\Models\RekapModel;
use App\Models\UserModel;
use App\Models\DivisionModel;
use DateTime;

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
            'fixed'       => $this->request->getPost('fixed'),
            'nullified'   => $this->request->getPost('nullified'),
        ];

        if ($id) {
            $this->rekapModel->update($id, $data);
        } else {
            $this->rekapModel->insert($data);
        }

        return redirect()->to('/rekap');
    }

public function printComplete()
{
     // Get inputs
    $startDate = $this->request->getGet('date_start');
    $endDate = $this->request->getGet('date_end');

    // Format dates
    $startDateObj = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);
    $startMonthName = $startDateObj->format('F');
    $endMonthName = $endDateObj->format('F');

    // Generate dynamic columns
    $dates = [];
    $dateMap = [];
    $columns = [];
    $period = new \DatePeriod(
        new \DateTime($startDate),
        new \DateInterval('P1D'),
        (new \DateTime($endDate))->modify('+1 day')
    );

    foreach ($period as $date) {
        $label = $date->format('M d');
        $dateString = $date->format('Y-m-d');
        $dates[] = $label;
        $dateMap[$label] = $dateString;
        $columns[] = "
            MAX(
                CASE 
                    WHEN DATE(p.created_at) = '$dateString' THEN p.status 
                    ELSE NULL 
                END
            ) AS `$label`";
    }

    // Build query - ALL divisions at once (no division filter),
    // grouped by user + division so a teacher in multiple divisions
    // correctly appears once per division.
    $db = \Config\Database::connect();
    $sql = "
    SELECT 
        u.id,
        u.name,
        ud.division_id,
        d.division_name,
        j.jabatan_nama,
        ud.nullified,
        ud.fixed,
        " . implode(",\n", $columns) . "
    FROM users u
    JOIN user_divisions ud ON ud.user_id = u.id
    JOIN divisions d ON d.id = ud.division_id
    LEFT JOIN user_position gj ON gj.guru_id = u.id
    LEFT JOIN position j ON j.jabatan_id = gj.jabatan_id 
    LEFT JOIN presensidata p 
        ON p.guru_id = u.id 
        AND DATE(p.created_at) BETWEEN '$startDate' AND '$endDate'
    WHERE u.deleted_at IS NULL
    GROUP BY u.id, ud.division_id
    ORDER BY d.id, u.name
    ";

    $query = $db->query($sql);
    $results = $query->getResult();

    if (count($results) < 1) {
        return "error"; // fallback
    }

    // Group results into one block per division
    $divisions = [];
    foreach ($results as $row) {
        $bid = $row->division_id;
        $divisions[$bid]['name'] = $row->division_name;
        $divisions[$bid]['rows'][] = $row;
    }

    return view('rekap/printcomplete', [
                'divisions' => array_values($divisions),
                'dates' => $dates,
                'dateMap' => $dateMap,
                'startMonth' => $startMonthName,
                'endMonth' => $endMonthName,
                'dateStart' => $startDateObj->format('d-m-Y'),
                'dateEnd' => $endDateObj->format('d-m-Y'),
            ]);

}

/**
 * Update attendance (presensi) for one teacher on one date.
 * Called via AJAX from the inline edit cells.
 */
public function updateAttendance()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['success' => false, 'message' => 'Not AJAX']);
    }

    $guruId = (int) $this->request->getPost('guru_id');
    $date   = $this->request->getPost('date');
    $statusRaw = $this->request->getPost('status'); // may be '' (empty) to clear

    if (!$guruId || !$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return $this->response->setJSON(['success' => false, 'message' => 'Invalid params']);
    }

    // status: '' empty -> delete existing; 0 -> delete; else 1,2,3,4
    if ($statusRaw === '' || $statusRaw === null || $statusRaw == 0) {
        $status = null;
    } else {
        $status = (int) $statusRaw;
        if (!in_array($status, [1, 2, 3, 4], true)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid status']);
        }
    }

    $db = \Config\Database::connect();

    // look for existing record on that date
    $existing = $db->table('presensidata')
        ->where('guru_id', $guruId)
        ->where('presensidata_tanggal', $date)
        ->where('deleted_at IS NULL')
        ->get()
        ->getRow();

    if ($status === null) {
        // delete existing (soft or hard): hard delete the row
        if ($existing) {
            $db->table('presensidata')->delete(['presensidata_id' => $existing->presensidata_id]);
        }
        return $this->response->setJSON(['success' => true, 'status' => null, 'message' => 'Cleared']);
    }

    $data = [
        'guru_id'              => $guruId,
        'presensidata_tanggal' => $date,
        'status'               => $status,
        'address'              => 'Admin Injected',
        'created_at'           => $date . ' 08:00:00',
        'updated_at'           => date('Y-m-d H:i:s'),
    ];

    if ($existing) {
        $db->table('presensidata')
            ->where('presensidata_id', $existing->presensidata_id)
            ->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    } else {
        $db->table('presensidata')->insert($data);
    }

    return $this->response->setJSON(['success' => true, 'status' => $status, 'message' => 'Saved']);
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
    ->where('status',1)
    ->groupBy('guru_id')
    ->getCompiledSelect();

$builder = $db->table('user_divisions r');

$rows = $builder
    ->select('
        r.user_group,
        r.group_sort,
        r.user_role,
        r.role_sort,
        r.user_id,
        r.nullified,
        r.fixed,
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
    $groupBuilder = $db->table('user_divisions');
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

        $userBuilder = $db->table('user_divisions r');

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