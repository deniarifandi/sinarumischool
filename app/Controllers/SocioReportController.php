<?php

namespace App\Controllers;

use App\Models\SocioReportModel;
use App\Models\ClassModel; // adjust if your class model name differs

class SocioReportController extends BaseController
{
    protected $reportModel;
    protected $classModel;

    public function __construct()
    {
        $this->reportModel = new SocioReportModel();
        $this->classModel  = new ClassModel();
    }

    /* ======================
       INDEX
       ====================== */
    public function index()
    {
     //   print_r(session()->get());
        $role       = session()->get('role');
        $division   = isset($_GET['divisi']) ? $_GET['divisi']  : 0;
        $userId     = session()->get('id');

        // classes for dropdown
        $classes = $this->classModel
            ->where('division_id', $division)
            ->findAll();

        // grouped data: class + month
        $builder = $this->reportModel
            ->select("
                socioreport.class_id,
                classes.class_name,
                DATE_FORMAT(socioreport.date,'%Y-%m') AS period,
                COUNT(*) AS total
            ")
            ->join('classes', 'classes.id = socioreport.class_id')
            ->groupBy('socioreport.class_id, period')
            ->orderBy('classes.class_name')
            ->orderBy('period', 'DESC')
            ->where('teacher_id',$userId)
            ;

        if ($role === 'teacher') {
            $builder->where('socioreport.teacher_id', $userId);
        }

        $periods = $builder->findAll();

        return view('socioreport/index', [
            'classes' => $classes,
            'periods' => $periods
        ]);
    }

    /* ======================
       IMPORT CSV
       ====================== */
    public function import()
{
    $classId   = (int)$this->request->getPost('class_id');
    $month     = (int)$this->request->getPost('month');
    $year      = (int)$this->request->getPost('year');
    
    $teacherId = session()->get('id');

    $file = $this->request->getFile('csv_file');
    if (!$file || !$file->isValid()) {
        return redirect()->back();
    }

    $periodDate = sprintf('%04d-%02d-01', $year, $month);
    $periodKey  = sprintf('%04d-%02d', $year, $month);

    // overwrite existing data (same class + month)
    $this->reportModel
        ->where('class_id', $classId)
        ->where("DATE_FORMAT(date,'%Y-%m')", $periodKey)
        ->delete();

    // read CSV
    $csv  = trim(file_get_contents($file->getTempName()));
    $rows = array_map('str_getcsv', preg_split("/\r\n|\n|\r/", $csv));
    if (count($rows) < 2) {
        return redirect()->back();
    }

    /* ======================
       COLUMN DETECTION
       (ported from old importer)
    ====================== */
    $columns = [
        'student'=>null,
        'trying'=>null,'following'=>null,
        'sharing'=>null,'kindness'=>null,
        'calm'=>null,'composed'=>null,
        'focused'=>null,'attempting'=>null,
        'cooperative'=>null,'listening'=>null,
        'accepting'=>null,'recovering'=>null,
        'improving'=>null,'challenging'=>null,
        'contributing'=>null,'supporting'=>null,
        'steady'=>null,'resilient'=>null,
        'giving'=>null,'reminders'=>null,'upset'=>null,
        'reactive'=>null,'not-sharing'=>null,'rough'=>null,
        'distracted'=>null,'avoiding'=>null,'rejecting'=>null,
        'quitting'=>null,'disruptive'=>null,'interrupting'=>null,
        'resisting'=>null,'avoidance'=>null,'overreacting'=>null,
        'lingering'=>null,'withdrawing'=>null,'dominating'=>null,
    ];

    foreach ($rows[0] as $x => $cell) {
        $val = strtolower(trim($cell));
        foreach ($columns as $key => $idx) {
            if ($idx === null && strpos($val, $key) !== false) {
                $columns[$key] = $x;
            }
        }
    }

    // hard stop if student column missing
    if ($columns['student'] === null) {
        return redirect()->back()
            ->with('error', 'CSV must contain student column');
    }

    /* ======================
       INSERT DATA
    ====================== */
    for ($i = 1; $i < count($rows); $i++) {
        $r = $rows[$i];
        if (empty(array_filter($r))) continue;

        $studentName = trim($r[$columns['student']] ?? '');
        if ($studentName === '') continue;

        $data = [
            'class_id'     => $classId,
            'teacher_id'   => $teacherId,
            'student_name' => $studentName,
            'date'         => $periodDate
        ];

        foreach ($columns as $key => $idx) {
            if ($key === 'student') continue;

            $data[$key] = ($idx !== null && isset($r[$idx]) && $r[$idx] !== '')
                ? (int)$r[$idx]
                : 0;
        }

        $this->reportModel->insert($data);
    }

    return redirect()->to('/socioreport?divisi=3');
}

    /* ======================
       PRINT
       ====================== */
    public function print($classId, $period)
    {
        $builder = $this->reportModel
            ->where('class_id', $classId)
            ->where("DATE_FORMAT(date,'%Y-%m')", $period)
            ->join('classes','classes.id = socioreport.class_id')
            ->join('grades','grades.id = classes.grade')
            ->orderBy('student_name');

        if (session()->get('role') === 'teacher') {
            $builder->where('teacher_id', session()->get('user_id'));
        }
        // echo $classId;

        $classDetail = $this->classModel->getClassDetail($classId);
        // print_r($this->classModel->getClassDetail($classId));
        // exit();

        return view('socioreport/print', [
            'class'   => $classDetail[0],
            'period'  => $period,
            'reports' => $builder->findAll()
        ]);
    }

    /* ======================
       DELETE (class + month)
       ====================== */
    public function deletePeriod($classId, $period)
    {
        $builder = $this->reportModel
            ->where('class_id', $classId)
            ->where("DATE_FORMAT(date,'%Y-%m')", $period);

        if (session()->get('role') === 'teacher') {
            $builder->where('teacher_id', session()->get('user_id'));
        }

        $builder->delete();

        return redirect()->to('/socioreport?divisi=3');
    }
}
