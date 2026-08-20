<?php

namespace App\Controllers;

use App\Models\StudentScoreModel;
use App\Models\GradebookScoreModel;

class StudentDashboardController extends BaseController
{
    protected $studentModel;
    protected $gradebookScoreModel;

    public function __construct()
    {
        $this->studentModel = new \App\Models\StudentModel();
        $this->gradebookScoreModel = new \App\Models\GradebookScoreModel();
    }

    public function directory()
    {   

        $division = $this->request->getGet('division');

        $students = $this->studentModel
            ->select('students.*, classes.class_name')
            ->join('classes', 'classes.id = students.class_id', 'left')
            // ->where('students.deleted_at', null)
            ->where('students.division_id',$division)
            ->orderBy('students.name', 'ASC')
            ->findAll();

        return view('student/report/directory', ['students' => $students]);
    }

public function dashboard($studentId)
{
    $student = $this->studentModel
        ->select('students.*, classes.class_name, classes.grade')
        ->join('classes', 'classes.id = students.class_id', 'left')
        ->where('students.id', $studentId)
        ->first();

    if (!$student) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Murid tidak ditemukan.');
    }

    // 1. Tangkap parameter filter subject dari URL (GET request)
    $selectedSubjectId = $this->request->getGet('subject_id');

    $gradeHistory = $this->gradebookScoreModel->getGradeHistory((int) $studentId);

    // 2. Kumpulkan daftar unik semua mata pelajaran untuk dropdown filter
    $availableSubjects = [];
    foreach ($gradeHistory as $row) {
        $availableSubjects[$row['subject_id']] = $row['subject_name'];
    }
    asort($availableSubjects); // Urutkan nama pelajaran secara alfabetis

    // 3. Group by term & Terapkan Filter
    $byTerm = [];
    foreach ($gradeHistory as $row) {
        // Jika filter aktif dan subject_id tidak cocok, lewati baris ini
        if (!empty($selectedSubjectId) && $row['subject_id'] != $selectedSubjectId) {
            continue;
        }

        if (!isset($byTerm[$row['term_id']])) {
            $byTerm[$row['term_id']] = [
                'term_name'          => $row['term_name'],
                'semester_name'      => $row['semester_name'],
                'academic_year_name' => $row['academic_year_name'],
                'class_name'         => $row['class_name'],
                'subjects'           => []
            ];
        }
        $byTerm[$row['term_id']]['subjects'][] = $row;
    }

    // 4. Chart data: average score per term
   // 4. Chart data: average score per term
    $chartLabels   = [];
    $chartAverages = [];
    $allScores     = [];
    $subjectIds    = [];

    foreach ($byTerm as $termId => $term) {
        $termScores = [];

        // Gunakan $index agar kita bisa memodifikasi array aslinya
        foreach ($term['subjects'] as $index => $s) {
            $subjectIds[$s['subject_id']] = true;

            $effectiveCt1 = $this->resolveScore($s['ct1'], $s['ct1_remedial']);
            $effectiveCt2 = $this->resolveScore($s['ct2'], $s['ct2_remedial']);

            $rowScores = []; // Array sementara untuk menghitung rata-rata per mata pelajaran

            foreach ([$effectiveCt1, $effectiveCt2] as $val) {
                if ($val !== null) {
                    $termScores[] = $val;
                    $allScores[]  = $val;
                    $rowScores[]  = $val;
                }
            }

            foreach (['individual_project', 'group_project'] as $field) {
                if (is_numeric($s[$field])) {
                    $termScores[] = (float) $s[$field];
                    $allScores[]  = (float) $s[$field];
                    $rowScores[]  = (float) $s[$field];
                }
            }

            // Hitung rata-rata per mata pelajaran dan simpan ke dalam array byTerm
            $byTerm[$termId]['subjects'][$index]['subject_average'] = !empty($rowScores) 
                ? round(array_sum($rowScores) / count($rowScores), 1) 
                : null;
        }

        // Hitung rata-rata per term
        if (!empty($termScores)) {
            $chartLabels[]   = $term['term_name'] . ' (' . $term['academic_year_name'] . ')';
            $chartAverages[] = round(array_sum($termScores) / count($termScores), 1);
        } else {
            unset($byTerm[$termId]); 
        }
    }

    $overallAverage = !empty($allScores) ? round(array_sum($allScores) / count($allScores), 1) : null;
    $totalSubjects  = count($subjectIds);
    
    // Gunakan jumlah term yang sudah disaring agar akurat saat difilter
    $totalTerms = count($byTerm);

    // Attendance (across all recorded terms — simple lifetime summary)
    $attendanceModel = $this->studentModel; 
    $lifetimeAttendance = null;

    if (!empty($byTerm)) {
        // Karena filter dapat mengubah jumlah data, kita ambil range tanggal asli dari $gradeHistory
        $allDates = array_column($gradeHistory, 'start_date');
        if (!empty($allDates)) {
            $earliest = min($allDates);
            $latest   = max($allDates);

            $lifetimeAttendance = $this->studentModel->getAttendanceSummary(
                (int) $studentId,
                (int) $student['class_id'], 
                $earliest,
                $latest
            );
        }
    }

    $byTermReversed = array_reverse($byTerm, true); 

    return view('student/report/dashboard', [
        'student'            => $student,
        'byTerm'             => $byTermReversed, 
        'chartLabels'        => $chartLabels,    
        'chartAverages'      => $chartAverages,  
        'overallAverage'     => $overallAverage,
        'totalSubjects'      => $totalSubjects,
        'totalTerms'         => $totalTerms,
        'lifetimeAttendance' => $lifetimeAttendance,
        // Kirim variabel baru ini ke View untuk membuat Dropdown Filter
        'availableSubjects'  => $availableSubjects,
        'selectedSubjectId'  => $selectedSubjectId,
    ]);
}

    public function dashboardOLD($studentId)
{
    $student = $this->studentModel
        ->select('students.*, classes.class_name, classes.grade')
        ->join('classes', 'classes.id = students.class_id', 'left')
        ->where('students.id', $studentId)
        ->first();

    if (!$student) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Murid tidak ditemukan.');
    }

    $gradeHistory = $this->gradebookScoreModel->getGradeHistory((int) $studentId);

    // Group by term
    $byTerm = [];
    foreach ($gradeHistory as $row) {
        $byTerm[$row['term_id']]['term_name']          = $row['term_name'];
        $byTerm[$row['term_id']]['semester_name']       = $row['semester_name'];
        $byTerm[$row['term_id']]['academic_year_name']  = $row['academic_year_name'];
        $byTerm[$row['term_id']]['class_name']          = $row['class_name'];
        $byTerm[$row['term_id']]['subjects'][]          = $row;
    }

    // Chart data: average score per term
   
    // Chart data: average score per term
    $chartLabels   = [];
    $chartAverages = [];
    $allScores     = [];
    $subjectIds    = [];

    foreach ($byTerm as $term) {
        $termScores = [];

        foreach ($term['subjects'] as $s) {
            $subjectIds[$s['subject_id']] = true;

            $effectiveCt1 = $this->resolveScore($s['ct1'], $s['ct1_remedial']);
            $effectiveCt2 = $this->resolveScore($s['ct2'], $s['ct2_remedial']);

            foreach ([$effectiveCt1, $effectiveCt2] as $val) {
                if ($val !== null) {
                    $termScores[] = $val;
                    $allScores[]  = $val;
                }
            }

            foreach (['individual_project', 'group_project'] as $field) {
                if (is_numeric($s[$field])) {
                    $termScores[] = (float) $s[$field];
                    $allScores[]  = (float) $s[$field];
                }
            }
        }

        $chartLabels[]   = $term['term_name'] . ' (' . $term['academic_year_name'] . ')';
        $chartAverages[] = !empty($termScores) ? round(array_sum($termScores) / count($termScores), 1) : null;
    }

    $overallAverage = !empty($allScores) ? round(array_sum($allScores) / count($allScores), 1) : null;

    $totalSubjects  = count($subjectIds);
    $totalTerms     = count($byTerm);

    // Attendance (across all recorded terms — simple lifetime summary)
    $attendanceModel = $this->studentModel; // getAttendanceSummary lives here per earlier setup
    $lifetimeAttendance = null;

    if (!empty($byTerm)) {
        // Use the earliest and latest term dates across history for a lifetime range
        $allDates = array_column($gradeHistory, 'start_date');
        if (!empty($allDates)) {
            $earliest = min($allDates);
            $latest   = max($allDates);

            $lifetimeAttendance = $this->studentModel->getAttendanceSummary(
                (int) $studentId,
                (int) $student['class_id'], // current class only — see note below
                $earliest,
                $latest
            );
        }
    }

    $byTermReversed = array_reverse($byTerm, true); // true preserves keys (term_id)

    return view('student/report/dashboard', [
        'student'            => $student,
        'byTerm'             => $byTermReversed, // table now uses newest-first
        'chartLabels'        => $chartLabels,     // unaffected — still chronological
        'chartAverages'      => $chartAverages,   // unaffected — still chronological
        'overallAverage'     => $overallAverage,
        'totalSubjects'      => $totalSubjects,
        'totalTerms'         => $totalTerms,
        'lifetimeAttendance' => $lifetimeAttendance,
    ]);
}

/**
 * Resolve the effective score for a subject: takes the higher of
 * the original attempt and its remedial (if remedial exists),
 * otherwise falls back to whichever is present.
 */
private function resolveScore($original, $remedial)
{
    $hasOriginal = is_numeric($original);
    $hasRemedial = is_numeric($remedial);

    if ($hasOriginal && $hasRemedial) {
        return max((float) $original, (float) $remedial);
    }

    if ($hasOriginal) {
        return (float) $original;
    }

    if ($hasRemedial) {
        return (float) $remedial;
    }

    return null; // no score recorded at all
}
}