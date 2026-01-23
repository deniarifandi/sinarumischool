<?php

namespace App\Controllers;

class Presence extends BaseController
{
 

    public function index()
{
    $db = \Config\Database::connect();
    $start = date('Y-m-d 00:00:00');
    $end   = date('Y-m-d 23:59:59');

    $presence = $db->table('presensidata')
        ->where('guru_id', session('id'))
        ->where('created_at >=', $start)
        ->where('created_at <=', $end)
        ->get()
        ->getResultArray();

        // print_r($presence);
        // exit();

    // pagination manual
    $perPage = 5;
    $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
    $offset  = ($page - 1) * $perPage;

    $total = $db->table('presensidata')
        ->where('guru_id', session('id'))
        ->countAllResults();

    $history = $db->table('presensidata')
        ->where('guru_id', session('id'))
        ->orderBy('presensidata_tanggal', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $totalPages = (int) ceil($total / $perPage);

    return view('presence/index', [
        'presence'   => $presence,
        'history'    => $history,
        'page'       => $page,
        'totalPages' => $totalPages
    ]);
}


   public function checkIn()
{
    $db = \Config\Database::connect();
    $userId = session('id');
    $today  = date('Y-m-d');

    // blok double submit
    $exists = $db->table('presensidata')
        ->where('guru_id', $userId)
        ->where('DATE(presensidata_tanggal)', $today)
        ->countAllResults();

    if ($exists > 0) {
        return redirect()->back()
            ->with('error', 'Attendance already submitted today');
    }

    $statusMap = [1,2,3];
    $status = (int) $this->request->getPost('status');

    if (!in_array($status, $statusMap, true)) {
        return redirect()->back()->with('error','Invalid status');
    }

    $db->table('presensidata')->insert([
        'guru_id' => $userId,
        'longitude' => $this->request->getPost('longitude'),
        'latitude' => $this->request->getPost('latitude'),
        'presensidata_tanggal' => date('Y-m-d H:i:s'),
        'status' => $status,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->back()
        ->with('success', 'Attendance recorded');
}

public function full_report($year = null, $month = null)
{
    $db = \Config\Database::connect();

    $year  = $year  ?? date('Y');
    $month = $month ?? date('m');

    // normalize month/year (handle <1 or >12)
    $current = strtotime(sprintf('%04d-%02d-01', $year, $month));
    $month   = (int)date('m', $current);
    $year    = (int)date('Y', $current);

    $start = date('Y-m-01', $current);
    $end   = date('Y-m-t', $current);

    $rows = $db->table('presensidata')
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
