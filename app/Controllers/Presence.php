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

    public function attendanceList()
{
    $perPage = (int) ($this->request->getGet('per_page') ?? 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?? 1));

    $date    = $this->request->getGet('date');      // exact date: 2026-04-22
    $start   = $this->request->getGet('start');     // range start
    $end     = $this->request->getGet('end');       // range end
    $search  = $this->request->getGet('search');    // keyword
    $status  = $this->request->getGet('status');    // 1,2,3

    $builder = $this->presence->where('guru_id', session('id'));

    // filter by exact day
    if ($date) {
        $builder->where('presensidata_tanggal >=', $date . ' 00:00:00')
                ->where('presensidata_tanggal <=', $date . ' 23:59:59');
    }

    // filter by range
    if ($start && $end) {
        $builder->where('presensidata_tanggal >=', $start . ' 00:00:00')
                ->where('presensidata_tanggal <=', $end . ' 23:59:59');
    }

    // filter by status
    if ($status !== null && $status !== '') {
        $builder->where('status', (int)$status);
    }

    // simple search (adjust fields if needed)
    if ($search) {
        $builder->groupStart()
                ->like('status', $search)
                ->orLike('presensidata_tanggal', $search)
                ->groupEnd();
    }

    $data = $builder
        ->orderBy('presensidata_tanggal', 'DESC')
        ->paginate($perPage);

    return view('rekap/attendance_list', [
        'data'  => $data,
        'pager' => $this->presence->pager,
        'filters' => [
            'date'   => $date,
            'start'  => $start,
            'end'    => $end,
            'search' => $search,
            'status' => $status
        ]
    ]);
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
//    public function checkIn()
// {
//     $this->db = \Config\Database::connect();
//     $userId = session('id');
//     $today  = date('Y-m-d');

//     // blok double submit
//     $exists = $this->db->table('presensidata')
//         ->where('guru_id', $userId)
//         ->where('DATE(presensidata_tanggal)', $today)
//         ->countAllResults();

//     if ($exists > 0) {
//         return redirect()->back()
//             ->with('error', 'Attendance already submitted today');
//     }

//     $statusMap = [1,2,3];
//     $status = (int) $this->request->getPost('status');

//     if (!in_array($status, $statusMap, true)) {
//         return redirect()->back()->with('error','Invalid status');
//     }

//     $this->db->table('presensidata')->insert([
//         'guru_id' => $userId,
//         'longitude' => $this->request->getPost('longitude'),
//         'latitude' => $this->request->getPost('latitude'),
//         'presensidata_tanggal' => date('Y-m-d H:i:s'),
//         'status' => $status,
//         'created_at' => date('Y-m-d H:i:s')
//     ]);

//     return redirect()->back()
//         ->with('success', 'Attendance recorded');
// }

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




}
