<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\UserModel;

class StudentController extends BaseController
{
    protected StudentModel $studentModel;


    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
        $this->userModel = new UserModel();
    }

   public function index()
{
    $divisionId = (int) $this->request->getGet('division');
    $user = session('id') ?? session('user_id');
    $userDetail = $this->userModel->getUserDetailData($user);
    $classId = $this->request->getGet('class');
    $classId = ($classId !== null && $classId !== '') ? (int) $classId : null;

    $students = $this->studentModel->studentDetail($divisionId, $classId);
    $classes  = $this->classModel->byDivision($divisionId);


    return view('student/index', [
        'students'   => $students,
        'divisionId' => $divisionId,
        'classes'    => $classes,
        'classId'    => $classId,
        'user'    => $userDetail[0]
    ]);
}

    public function create()
    {
        $divisionId = (int) $this->request->getGet('division');

        $classes = $this->classModel->byDivision($divisionId);

        return view('student/form',[ 'divisionId' => $divisionId ,'classes' => $classes ]);
    }

    public function edit($id)
    {
        $student = $this->studentModel->find($id);
        $divisionId = $this->request->getGet('division');

        if (!$student) {
            return redirect()->to('student')->with('error', 'Student not found');
        }

        $classes = $this->classModel->byDivision($divisionId);

       // return view('student/form',[ 'divisionId' => $divisionId ]);

        return view('student/form', [
            'student'   => $student,
            'divisionId'  => $divisionId,
            'classes' => $classes 
        ]);
    }

    public function save($id = null)
    {
        $data = [
            'division_id'   => (int) $this->request->getPost('division_id'),
            'class_id'      => (int) $this->request->getPost('class_id'),
            'name'          => trim((string) $this->request->getPost('name')),
            'gender'        => $this->request->getPost('gender'),
            'birthdate'     => $this->request->getPost('birthdate'),
            'student_code'  => trim((string) $this->request->getPost('student_code')),
            'address'       => $this->request->getPost('address'),
            'murid_agama'   => $this->request->getPost('murid_agama'),
        ];

        if ($data['name'] === '') {
            return redirect()->back()->withInput()->with('error', 'Name is required');
        }

        if ($id) {
            $this->studentModel->update($id, $data);
        } else {
            $this->studentModel->insert($data);
        }

        return redirect()->to('student?division=' . $data['division_id'])
            ->with('success', 'Saved');
    }

    public function delete($id)
    {
        $this->studentModel->delete($id);

        return redirect()->back()->with('success', 'Deleted');
    }

    public function createAttendance($class_id)
{
    $db = \Config\Database::connect();

    $rows = $db->table('students')
        ->select('id as murid_id, name')
        ->where('class_id', $class_id)
        ->orderBy('name', 'ASC')
        ->where('students.deleted_at',NULL)
        ->get()
        ->getResultArray();
    // print_r($rows);
    // exit();

    return view('student/attendanceForm', [
        'data'      => $rows,
        'class_id'  => $class_id,
        'tanggal'   => date('Y-m-d'),
        'isEdit'    => false
    ]);
}

    public function formAttendance($class_id, $tanggal = null)
{
    $db = \Config\Database::connect();

    $tanggal = $tanggal ?? date('Y-m-d');

    $rows = $db->table('students s')
        ->select('s.id as murid_id, s.name, a.status, a.absensi_keterangan')
        ->join('absensi a', 'a.murid_id = s.id AND a.tanggal = "'.$tanggal.'"', 'left', false)
        ->where('s.class_id', $class_id)
        ->orderBy('s.name', 'ASC')
        ->get()
        ->getResultArray();

    // cek apakah sudah ada data (mode edit)
    $isEdit = false;
    foreach ($rows as $r) {
        if ($r['status'] !== null) {
            $isEdit = true;
            break;
        }
    }

    return view('student/attendanceForm', [
        'data'      => $rows,
        'class_id'  => $class_id,
        'tanggal'   => $tanggal,
        'isEdit'    => $isEdit
    ]);
}

// public function editAttendance($class_id, $tanggal)
// {
//     $db = \Config\Database::connect();

//     $data = $db->table('murid m')
//         ->select('m.murid_id, m.name, a.status, a.absensi_keterangan')
//         ->join('absensi a', 'a.murid_id = m.murid_id AND a.tanggal = "'.$tanggal.'"', 'left')
//         ->where('m.class_id', $class_id)
//         ->get()
//         ->getResultArray();

//     return view('attendance/form', [
//         'class_id' => $class_id,
//         'tanggal'  => $tanggal,
//         'data'     => $data,
//         'is_edit'  => true
//     ]);
// }

    
    public function attendanceList($class_id)
{
    $db = \Config\Database::connect();

    $rows = $db->table('absensi a')
        ->select('a.tanggal, c.class_name')
        ->join('students s', 's.id = a.murid_id')
        ->join('classes c', 'c.id = s.class_id')
        ->where('s.class_id', $class_id)
        ->groupBy('a.tanggal')
        ->orderBy('a.tanggal', 'DESC')
        ->get()
        ->getResultArray();

    $academicYear = '';

    $date = new \DateTime();

    if ((int)$date->format('n') >= 7) {
        $startYear = (int)$date->format('Y');
    } else {
        $startYear = (int)$date->format('Y') - 1;
    }

    $startDate = $startYear . '-07-01';
    $endDate   = ($startYear + 1) . '-06-30';

    $rows = $db->table('absensi a')
    ->select('a.tanggal, c.class_name')
    ->join('students s', 's.id = a.murid_id')
    ->join('classes c', 'c.id = s.class_id')
    ->where('s.class_id', $class_id)
    ->where('a.tanggal >=', $startDate)
    ->where('a.tanggal <=', $endDate)
    ->groupBy('a.tanggal')
    ->orderBy('a.tanggal', 'DESC')
    ->get()
    ->getResultArray();

    return view('student/attendanceList', [
        'dates'         => $rows,
        'academicYear'  => $academicYear,
        'class_id'      => $class_id,
    ]);
}
     public function simpan()
{
    $db = \Config\Database::connect();
    $builder = $db->table('absensi');

    $murid_ids  = $this->request->getPost('student_id');
    $status     = $this->request->getPost('status');      // keyed by murid_id
    $keterangan = $this->request->getPost('keterangan');  // keyed by murid_id
    $tanggal    = $this->request->getPost('tanggal') ?? date('Y-m-d');
    $class_id   = $this->request->getPost('class_id');

    if (empty($murid_ids)) {
        return redirect()->back();
    }

    $now = date('Y-m-d H:i:s');

    foreach ($murid_ids as $id) {

        $data = [
            'status'             => $status[$id] ?? null,
            'absensi_keterangan' => $keterangan[$id] ?? null,
            'updated_at'         => $now
        ];

        $exists = $builder
            ->where('murid_id', $id)
            ->where('tanggal', $tanggal)
            ->get()
            ->getRowArray();

        if ($exists) {
            $builder
                ->where('murid_id', $id)
                ->where('tanggal', $tanggal)
                ->update($data);
        } else {
            $data['murid_id']   = $id;
            $data['tanggal']    = $tanggal;
          
            $data['created_at'] = $now;

            $builder->insert($data);
        }
    }

    return redirect()->to('/student/attendance/list/class/' . $class_id);
}

public function attendanceDetail($class_id, $tanggal)
{
    $db = \Config\Database::connect();

    $rows = $db->table('students s')
        ->select('s.id as murid_id, s.name, a.status, a.absensi_keterangan, a.tanggal, c.class_name')
        ->join('classes c', 'c.id = s.class_id','left')
        ->join('absensi a', 'a.murid_id = s.id AND a.tanggal = "'.$tanggal.'"', 'left', false)
        ->where('s.class_id', $class_id)
        ->orderBy('s.name', 'ASC')
        ->where('s.deleted_at',null)
        ->get()
        ->getResultArray();

    return view('student/attendanceDetail', [
        'data'      => $rows,
        'class_id'  => $class_id,
        'tanggal'   => $tanggal
    ]);
}
public function editAttendance($class_id, $tanggal)
{
    $db = \Config\Database::connect();

    $data = $db->table('students s')
        ->select('
            s.id AS murid_id,
            s.name,
            a.status,
            a.absensi_keterangan
        ')
        ->join(
            'absensi a',
            "a.murid_id = s.id AND a.tanggal = ".$db->escape($tanggal),
            'left'
        )
        ->where('s.class_id', $class_id)
        ->orderBy('s.name')
        ->get()
        ->getResultArray();

    return view('student/editAttendance', [
        'data' => $data,
        'tanggal' => $tanggal,
        'class_id' => $class_id
    ]);
}

public function updateAttendance()
{
    $db = \Config\Database::connect();

    $tanggal = $this->request->getPost('tanggal');
    $class_id = $this->request->getPost('class_id');
    $students = $this->request->getPost('student_id');
    $status = $this->request->getPost('status');
    $keterangan = $this->request->getPost('keterangan');

    foreach ($students as $id) {

        $db->table('absensi')
            ->where('murid_id', $id)
            ->where('tanggal', $tanggal)
            ->update([
                'status' => $status[$id],
                'absensi_keterangan' => $keterangan[$id],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    return redirect()->to('/student/attendance/list/class/'.$class_id);
}

public function attendanceRecap($class_id)
{
    $db = \Config\Database::connect();

    $class = $db->table('classes')
        ->where('id', $class_id)
        ->get()
        ->getRowArray();

    return view('student/attendanceRecap', [
        'class_id'  => $class_id,
        'class_name'=> $class['class_name'] ?? '',
        'month'     => date('m'),
        'year'      => date('Y'),
        'data'      => []
    ]);
}

public function attendanceRecapResult()
{
    $db = \Config\Database::connect();

    $class_id = $this->request->getPost('class_id');
    $month    = (int) $this->request->getPost('month');
    $year     = (int) $this->request->getPost('year');

    $startDate = sprintf('%04d-%02d-01', $year, $month);
    $endDate   = date('Y-m-t', strtotime($startDate));

    $class = $db->table('classes')
        ->where('id', $class_id)
        ->get()
        ->getRowArray();

    $rows = $db->query("
        SELECT
            s.id,
            s.name,
            SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) AS hadir,
            SUM(CASE WHEN a.status = 2 THEN 1 ELSE 0 END) AS izin,
            SUM(CASE WHEN a.status = 3 THEN 1 ELSE 0 END) AS sakit,
            SUM(CASE WHEN a.status = 4 THEN 1 ELSE 0 END) AS alpha
        FROM students s
        LEFT JOIN absensi a
            ON a.murid_id = s.id
            AND a.tanggal BETWEEN ? AND ?
        WHERE s.class_id = ?
        GROUP BY s.id, s.name
        ORDER BY s.name
    ", [
        $startDate,
        $endDate,
        $class_id
    ])->getResultArray();

    return view('student/attendanceRecap', [
        'class_id'   => $class_id,
        'class_name' => $class['class_name'] ?? '',
        'month'      => $month,
        'year'       => $year,
        'data'       => $rows
    ]);
}
}
