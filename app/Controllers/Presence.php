<?php

namespace App\Controllers;

use App\Models\PresenceModel;

class Presence extends BaseController
{

    public $start;
    public $end;
    public $db;
    public $presence;

     public function __construct()
    {   
        $this->db = \Config\Database::connect();
        $this->presence = new PresenceModel();
    }

    public function index()
    {
    
        $checkedToday = $this->presence->presence_check(session('id'));
        //$presence = $this->presence_check();

        // pagination manual
        $perPage = 5;
        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $offset  = ($page - 1) * $perPage;

        $total = $this->db->table('presensidata')
            ->where('guru_id', session('id'))
            ->countAllResults();

        $history = $this->db->table('presensidata')
            ->where('guru_id', session('id'))
            ->orderBy('presensidata_tanggal', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        $totalPages = (int) ceil($total / $perPage);

        return view('presence/index', [
            'presence'   => $checkedToday,
            'history'    => $history,
            'page'       => $page,
            'totalPages' => $totalPages
        ]);
}

// php_uname()

public function attendancePage()
{
     $users = $this->db->table('users')
        ->select('id, name')
        ->get()
        ->getResultArray();

    return view('rekap/attendance_list', [
        'allUsers' => $users
    ]);
}

public function attendanceData()
{
    $request = $this->request->getGet();

    $builder = $this->db->table('presensidata')
        ->select('presensidata.*, users.name')
        ->join('users', 'users.id = presensidata.guru_id');

    $dt = new \App\Libraries\DataTables($builder, $request);

    $dt->setColumns([
        'presensidata.presensidata_id',
        'users.name',
        'presensidata.presensidata_tanggal',
        'presensidata.status',
        'presensidata.latitude',
        'presensidata.longitude',
        'presensidata.address',
        'presensidata.created_at',
    ]);

    $data = $dt
        ->applySearch([
            'users.name',
            'presensidata.address'
        ])
        ->applyFilters(function ($builder, $request) {

            if (!empty($request['date'])) {
                $builder->where('presensidata.presensidata_tanggal', $request['date']);
            }

            if (!empty($request['status'])) {
                $builder->where('presensidata.status', $request['status']);
            }
        })
        ->applyOrdering()
        ->paginate()
        ->result();

    $recordsFiltered = $dt->countFiltered();
    $recordsTotal    = $dt->countAll('presensidata');

    return $this->response->setJSON([
        'draw' => (int)($request['draw'] ?? 1),
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ]);
}

public function attendanceDataOLD2()
{
    $request = service('request');

    $draw   = (int) $request->getGet('draw');
    $start  = (int) $request->getGet('start');
    $length = (int) $request->getGet('length');

    $search = $request->getGet('search')['value'] ?? '';

    $date   = $request->getGet('date');
    $startDate = $request->getGet('startDate');
    $endDate   = $request->getGet('endDate');
    $status = $request->getGet('status');

    $builder = $this->db->table('presensidata')
        ->select('
            presensidata.presensidata_id,
            presensidata.presensidata_tanggal,
            presensidata.status,
            presensidata.latitude,
            presensidata.longitude,
            presensidata.address,
            presensidata.created_at,
            users.name
        ')
        ->join('users', 'users.id = presensidata.guru_id');

    // FILTERS
    if (!empty($date)) {
        $builder->where('presensidata.presensidata_tanggal', $date);
    } elseif (!empty($startDate) && !empty($endDate)) {
        $builder->where('presensidata.presensidata_tanggal >=', $startDate);
        $builder->where('presensidata.presensidata_tanggal <=', $endDate);
    }

    if (!empty($status)) {
        $builder->where('presensidata.status', $status);
    }

    // SEARCH
    if (!empty($search)) {
        $builder->groupStart()
            ->like('users.name', $search)
            ->orLike('presensidata.address', $search)
            ->groupEnd();
    }

    // TOTAL FILTERED
    $filteredBuilder = clone $builder;
    $recordsFiltered = $filteredBuilder->countAllResults(false);

    // ORDER
    $builder->orderBy('presensidata.created_at', 'DESC');

    // PAGINATION
    $data = $builder
        ->limit($length, $start)
        ->get()
        ->getResultArray();

    // TOTAL ALL
    $recordsTotal = $this->db->table('presensidata')->countAll();

    return $this->response->setJSON([
        'draw' => $draw,
        'recordsTotal' => $recordsTotal,
        'recordsFiltered' => $recordsFiltered,
        'data' => $data
    ]);
}

public function attendancePageOLD()
{
    $builder = $this->db->table('presensidata')
        ->select('presensidata.*, users.name')
        ->join('users', 'users.id = presensidata.guru_id');

    // FILTERS
    $date   = $this->request->getGet('date');
    $start  = $this->request->getGet('start');
    $end    = $this->request->getGet('end');
    $status = $this->request->getGet('status');

    if (!empty($date)) {
        $builder->where('presensidata_tanggal', $date);
    }

    if (!empty($start) && !empty($end)) {
        $builder->where('presensidata_tanggal >=', $start);
        $builder->where('presensidata_tanggal <=', $end);
    }

    if (!empty($status)) {
        $builder->where('status', $status);
    }

    $history = $builder
        ->orderBy('presensidata_tanggal', 'DESC')
        ->limit(10000)
        ->get()
        ->getResultArray();

    return view('rekap/attendance_list', [
        'users' => $history
    ]);
}
   
   private function insertAttendance(array $data)
{
    $userId = $data['guru_id'];

   $submittedDate = $data['date'];

$start = $submittedDate . ' 00:00:00';
$end   = $submittedDate . ' 23:59:59';

    $exists = $this->db->table('presensidata')
        ->where('guru_id', $userId)
        ->where('presensidata_tanggal >=', $start)
        ->where('presensidata_tanggal <=', $end)
        ->countAllResults();

    if ($exists) {
        return ['ok' => false, 'msg' => 'Attendance already submitted today'];
    }

    $this->db->table('presensidata')->insert([
        'guru_id' => $userId,
        'status' => (int) $data['status'],
        'latitude' => 0,
        'longitude' => 0,
        'address' => "Admin Injected",
        'presensidata_tanggal' => $data['date'] . ' ' . date('H:i:s'),
        'created_at' => $data['date'] . ' ' . date('H:i:s'),
    ]);

    return ['ok' => true];
}

public function storeAttendance()
{

    $result = $this->insertAttendance([
        'guru_id' => $this->request->getPost('guru_id'),
        'status' => $this->request->getPost('status'),
        'date' => $this->request->getPost('date'),
    ]);

    if (!$result['ok']) {
        return redirect()->back()->with('error', $result['msg']);
    }

    return redirect()->back()->with('success', 'Attendance added');
}

public function checkIn()
{
    $userId = session('id');

    $start = date('Y-m-d 00:00:00');
    $end   = date('Y-m-d 23:59:59');

    // prevent double submit (index-friendly)
    $exists = $this->db->table('presensidata')
        ->where('guru_id', $userId)
        ->where('presensidata_tanggal >=', $start)
        ->where('presensidata_tanggal <=', $end)
        ->countAllResults();

    if ($exists) {
        return redirect()->back()->with('error', 'Attendance already submitted today');
    }

    $status = (int) $this->request->getPost('status');
    if (!in_array($status, [1,2,3], true)) {
        return redirect()->back()->with('error','Invalid status');
    }

    $lat = $this->request->getPost('latitude');
    $lng = $this->request->getPost('longitude');
    $address = $this->request->getPost('address');

    // basic validation
    if (!$lat || !$lng) {
        return redirect()->back()->with('error','Location not detected');
    }

    $this->db->table('presensidata')->insert([
        'guru_id' => $userId,
        'longitude' => $lng,
        'latitude' => $lat,
        'address' => $address, // <-- added
        'presensidata_tanggal' => date('Y-m-d H:i:s'),
        'status' => $status,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()->with('success', 'Attendance recorded');
}


public function full_report($year = null, $month = null)
{
    $this->db = \Config\Database::connect();

    $year  = $year  ?? date('Y');
    $month = $month ?? date('m');

    // normalize month/year (handle <1 or >12)
    $current = strtotime(sprintf('%04d-%02d-01', $year, $month));
    $month   = (int)date('m', $current);
    $year    = (int)date('Y', $current);

    $start = date('Y-m-01', $current);
    $end   = date('Y-m-t', $current);

    $rows = $this->db->table('presensidata')
        ->select('DATE(presensidata_tanggal) as tgl, status')
        ->where('guru_id', session('id'))
        ->where('presensidata_tanggal >=', $start)
        ->where('presensidata_tanggal <=', $end)
        ->get()
        ->getResultArray();

    $attendance = [];
    foreach ($rows as $r) {
        $attendance[$r['tgl']] = $r['status'];
    }

    return view('presence/full_report', [
        'month'      => $month,
        'year'       => $year,
        'attendance' => $attendance
    ]);
}

public function updateStatus()
{
    $id = $this->request->getPost('presensidata_id');
    $status = (int) $this->request->getPost('status');

    if (!in_array($status, [1,2,3], true)) {
        return redirect()->back()->with('error', 'Invalid status');
    }

    $this->db->table('presensidata')
        ->where('presensidata_id', $id)
        ->update([
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    return redirect()->back()->with('success', 'Status updated');
}




}
