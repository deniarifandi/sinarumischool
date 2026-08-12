<?php

namespace App\Controllers;

use App\Models\SlaModel;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\DivisionModel;


class SlaController extends BaseController
{
    protected $slaModel;
    protected $classModel;
    protected $studentModel;
    protected $divisionModel;

    public function __construct()
    {
        $this->slaModel      = new SlaModel();
        $this->classModel    = new ClassModel();
        $this->studentModel  = new StudentModel();
        $this->divisionModel = new DivisionModel();
    }


    /**
     * Display all Late Arrival Slips
     */
    public function index()
    {
        $divisionId = $this->request->getGet('division');

        if (!$divisionId) {
            return redirect()
                ->to('/sla')
                ->with('error', 'Division is required.');
        }

        $division = $this->divisionModel->find($divisionId);

        if (!$division) {
            return redirect()
                ->to('/sla')
                ->with('error', 'Division not found.');
        }

        // Get classes for filter
        $classes = $this->classModel
            ->where('division_id', $divisionId)
            ->orderBy('class_name', 'ASC')
            ->findAll();

        $builder = $this->slaModel
            ->select('
                slas.*,
                students.name AS student_name,
                students.student_code,
                students.class_id,
                classes.class_name,
                users.name AS teacher_name
            ')
            ->join(
                'students',
                'students.id = slas.student_id'
            )
            ->join(
                'classes',
                'classes.id = students.class_id'
            )
            ->join(
                'users',
                'users.id = slas.teacher_id',
                'left'
            )
            ->where('students.division_id', $divisionId);

        // Date filter
       // Month filter
$month = $this->request->getGet('month');

if ($month) {
    $builder->where(
        'MONTH(slas.arrivaltime)',
        (int) $month
    );
}


// Year filter
$year = $this->request->getGet('year');

if ($year) {
    $builder->where(
        'YEAR(slas.arrivaltime)',
        (int) $year
    );
}

        // Class filter
        $classId = $this->request->getGet('class_id');

        if ($classId) {
            $builder->where(
                'students.class_id',
                $classId
            );
        }

        $slas = $builder
            ->orderBy('slas.arrivaltime', 'DESC')
            ->findAll();

        $export = $this->request->getGet('export');

        if ($export === 'excel') {
            return $this->exportExcel($slas, $division);
        }

        if ($export === 'resume') {
            return $this->exportResume($slas, $division);
        }    

        $data = [
            'title'       => 'Student Late Arrival Slips',
            'division'    => $division,
            'division_id' => $divisionId,
            'classes'     => $classes,
            'slas'        => $slas,
        ];

        return view('sla/index', $data);
    }

    /**
 * Export Late Arrival Slips to Excel
 */
private function exportExcel(array $slas, array $division)
{
    $divisionName = $division['name']
        ?? $division['division_name']
        ?? 'Division';

    $filename = 'late_arrival_slips_' . date('Y-m-d_H-i-s') . '.xls';

    $html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #000;
                padding: 6px;
            }

            th {
                background-color: #eeeeee;
                font-weight: bold;
            }

            .title {
                font-size: 18px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <table>
            <tr>
                <td colspan="9" class="title">
                    STUDENT LATE ARRIVAL SLIPS
                </td>
            </tr>

            <tr>
                <td colspan="9">
                    Division: ' . esc($divisionName) . '
                </td>
            </tr>

            <tr>
                <td colspan="9"></td>
            </tr>

            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Student Code</th>
                <th>Class</th>
                <th>Arrival Time</th>
                <th>Problem</th>
                <th>Reason</th>
                <th>Point</th>
                <th>Officer</th>
            </tr>
    ';

    foreach ($slas as $index => $sla) {

        $arrivalTime = '';

        if (!empty($sla['arrivaltime'])) {
            $arrivalTime = date(
                'd M Y H:i',
                strtotime($sla['arrivaltime'])
            );
        }

        $html .= '
            <tr>
                <td>' . ($index + 1) . '</td>

                <td>' . esc($sla['student_name'] ?? '') . '</td>

                <td>' . esc($sla['student_code'] ?? '') . '</td>

                <td>' . esc($sla['class_name'] ?? '') . '</td>

                <td>' . esc($arrivalTime) . '</td>

                <td>' . esc($sla['problem'] ?? '') . '</td>

                <td>' . esc($sla['reason'] ?? '') . '</td>

                <td>' . esc($sla['reduction'] ?? '') . '</td>

                <td>' . esc($sla['teacher_name'] ?? '') . '</td>
            </tr>
        ';
    }

    $html .= '
        </table>

    </body>
    </html>
    ';

    return $this->response
        ->setHeader(
            'Content-Type',
            'application/vnd.ms-excel; charset=UTF-8'
        )
        ->setHeader(
            'Content-Disposition',
            'attachment; filename="' . $filename . '"'
        )
        ->setBody($html);
}

/**
 * Convert number to Excel column
 */


/**
 * Export Student Late Arrival Resume
 */
private function exportResumeOLD(array $slas, array $division)
{
    $divisionName = $division['name']
        ?? $division['division_name']
        ?? 'Division';

    $resume = [];

    foreach ($slas as $sla) {

        $studentId = $sla['student_id'];

        if (!isset($resume[$studentId])) {
            $resume[$studentId] = [
                'student_name'     => $sla['student_name'] ?? '',
                'student_code'     => $sla['student_code'] ?? '',
                'class_name'       => $sla['class_name'] ?? '',
                'total_late'       => 0,
                'total_reduction'  => 0,
            ];
        }

        $resume[$studentId]['total_late']++;

        $resume[$studentId]['total_reduction'] +=
            (float) ($sla['reduction'] ?? 0);
    }

    usort($resume, function ($a, $b) {

        $classCompare = strcmp(
            $a['class_name'],
            $b['class_name']
        );

        if ($classCompare !== 0) {
            return $classCompare;
        }

        return strcmp(
            $a['student_name'],
            $b['student_name']
        );
    });

    $filename = 'student_late_arrival_resume_' . date('Y-m-d_H-i-s') . '.xls';

    $html = '
    <html>
    <head>
        <meta charset="UTF-8">

        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #000;
                padding: 6px;
            }

            th {
                background-color: #eeeeee;
                font-weight: bold;
            }

            .title {
                font-size: 18px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <table>

            <tr>
                <td colspan="6" class="title">
                    STUDENT LATE ARRIVAL RESUME
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    Division: ' . esc($divisionName) . '
                </td>
            </tr>

            <tr>
                <td colspan="6"></td>
            </tr>

            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Student Code</th>
                <th>Class</th>
                <th>Total Late Arrival</th>
                <th>Total Point Reduction</th>
            </tr>
    ';

    foreach ($resume as $index => $student) {

        $html .= '
            <tr>

                <td>' . ($index + 1) . '</td>

                <td>' . esc($student['student_name']) . '</td>

                <td>' . esc($student['student_code']) . '</td>

                <td>' . esc($student['class_name']) . '</td>

                <td>' . $student['total_late'] . '</td>

                <td>' . $student['total_reduction'] . '</td>

            </tr>
        ';
    }

    $html .= '
        </table>

    </body>
    </html>
    ';

    return $this->response
        ->setHeader(
            'Content-Type',
            'application/vnd.ms-excel; charset=UTF-8'
        )
        ->setHeader(
            'Content-Disposition',
            'attachment; filename="' . $filename . '"'
        )
        ->setBody($html);
}

private function exportResume(array $slas, array $division)
{
    $divisionName = $division['name']
        ?? $division['division_name']
        ?? 'Division';

    $resume = [];

    foreach ($slas as $sla) {

        $studentId = $sla['student_id'];

        if (!isset($resume[$studentId])) {
            $resume[$studentId] = [
                'student_name'    => $sla['student_name'] ?? '',
                'student_code'    => $sla['student_code'] ?? '',
                'class_name'      => $sla['class_name'] ?? '',
                'total_late'      => 0,
                'total_reduction' => 0,
            ];
        }

        $resume[$studentId]['total_late']++;

        $resume[$studentId]['total_reduction'] +=
            (float) ($sla['reduction'] ?? 0);
    }

    // Sort by most late arrivals first
    // If the total is the same:
    // 1. Sort by class
    // 2. Sort by student name
    usort($resume, function ($a, $b) {

        if ($a['total_late'] !== $b['total_late']) {
            return $b['total_late'] <=> $a['total_late'];
        }

        $classCompare = strcmp(
            $a['class_name'],
            $b['class_name']
        );

        if ($classCompare !== 0) {
            return $classCompare;
        }

        return strcmp(
            $a['student_name'],
            $b['student_name']
        );
    });

    $filename = 'student_late_arrival_resume_' . date('Y-m-d_H-i-s') . '.xls';

    $html = '
    <html>
    <head>
        <meta charset="UTF-8">

        <style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #000;
                padding: 6px;
            }

            th {
                background-color: #eeeeee;
                font-weight: bold;
            }

            .title {
                font-size: 18px;
                font-weight: bold;
            }
        </style>
    </head>

    <body>

        <table>

            <tr>
                <td colspan="6" class="title">
                    STUDENT LATE ARRIVAL RESUME
                </td>
            </tr>

            <tr>
                <td colspan="6">
                    Division: ' . esc($divisionName) . '
                </td>
            </tr>

            <tr>
                <td colspan="6"></td>
            </tr>

            <tr>
                <th>No.</th>
                <th>Student</th>
                <th>Student Code</th>
                <th>Class</th>
                <th>Total Late Arrival</th>
                <th>Total Point Reduction</th>
            </tr>
    ';

    foreach ($resume as $index => $student) {

        $html .= '
            <tr>

                <td>' . ($index + 1) . '</td>

                <td>' . esc($student['student_name']) . '</td>

                <td>' . esc($student['student_code']) . '</td>

                <td>' . esc($student['class_name']) . '</td>

                <td>' . $student['total_late'] . '</td>

                <td>' . $student['total_reduction'] . '</td>

            </tr>
        ';
    }

    $html .= '
        </table>

    </body>
    </html>
    ';

    return $this->response
        ->setHeader(
            'Content-Type',
            'application/vnd.ms-excel; charset=UTF-8'
        )
        ->setHeader(
            'Content-Disposition',
            'attachment; filename="' . $filename . '"'
        )
        ->setBody($html);
}


    /**
     * Create Late Arrival Slip
     */
    public function create()
    {
        $divisionId = $this->request->getGet('division');
        $userId     = session()->get('id');

        if (!$divisionId) {
            return redirect()
                ->to('/sla')
                ->with('error', 'Division is required.');
        }

        if (!$userId) {
            return redirect()
                ->to('/login')
                ->with('error', 'Please login first.');
        }

        $division = $this->divisionModel->find($divisionId);

        if (!$division) {
            return redirect()
                ->to('/sla')
                ->with('error', 'Division not found.');
        }

        $classes = $this->classModel
            ->where('division_id', $divisionId)
            ->orderBy('class_name', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Create Student Late Arrival Slip',
            'division_id' => $divisionId,
            'teacher_id'  => $userId,
            'classes'     => $classes,
        ];

        return view('sla/create', $data);
    }


    /**
     * Get students based on selected class and division
     */
    public function studentsByClass($classId)
    {
        $divisionId = $this->request->getGet('division');

        if (!$divisionId) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Division is required.',
                ]);
        }

        $class = $this->classModel
            ->where('id', $classId)
            ->where('division_id', $divisionId)
            ->first();

        if (!$class) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status'  => false,
                    'message' => 'Class not found.',
                ]);
        }

        $students = $this->studentModel
            ->where('class_id', $classId)
            ->where('division_id', $divisionId)
            ->where('deleted_at IS NULL', null, false)
            ->orderBy('name', 'ASC')
            ->findAll();

        return $this->response
            ->setJSON($students);
    }


    /**
     * Store new Late Arrival Slip
     */
    public function store()
    {
        $userId     = session()->get('id');
        $divisionId = $this->request->getPost('division_id');
        $studentId  = $this->request->getPost('student_id');

        if (!$userId) {
            return redirect()
                ->to('/login')
                ->with('error', 'Please login first.');
        }

        if (!$divisionId) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Division is required.');
        }

        // Verify student belongs to division
        $student = $this->studentModel
            ->where('id', $studentId)
            ->where('division_id', $divisionId)
            ->where('deleted_at IS NULL', null, false)
            ->first();

        if (!$student) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid student selected.');
        }

        $rules = [
            'student_id'  => 'required|numeric',
            'arrivaltime' => 'required',
            'problem'     => 'required',
            'reason'      => 'required',
            'reduction'   => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->slaModel->insert([
            'teacher_id'  => $userId,
            'student_id'  => $studentId,
            'arrivaltime' => $this->request->getPost('arrivaltime'),
            'problem'     => $this->request->getPost('problem'),
            'reason'      => $this->request->getPost('reason'),
            'reduction'   => $this->request->getPost('reduction'),
        ]);

        return redirect()
            ->to('/sla?division=' . $divisionId)
            ->with('success', 'Late arrival slip successfully created.');
    }


    /**
     * Edit Late Arrival Slip
     */
    public function edit($id)
    {
        $sla = $this->slaModel
            ->select('
                slas.*,
                students.class_id,
                students.division_id
            ')
            ->join(
                'students',
                'students.id = slas.student_id'
            )
            ->where('slas.id', $id)
            ->first();

        if (!$sla) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Late arrival slip not found.'
            );
        }

        $divisionId = $sla['division_id'];

        $classes = $this->classModel
            ->where('division_id', $divisionId)
            ->orderBy('class_name', 'ASC')
            ->findAll();

        $data = [
            'title'       => 'Edit Late Arrival Slip',
            'sla'         => $sla,
            'division_id' => $divisionId,
            'classes'     => $classes,
        ];

        return view('sla/edit', $data);
    }


    /**
     * Update Late Arrival Slip
     */
    public function update($id)
    {
        $sla = $this->slaModel->find($id);

        if (!$sla) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Late arrival slip not found.'
            );
        }

        $divisionId = $this->request->getPost('division_id');
        $studentId  = $this->request->getPost('student_id');

        if (!$divisionId) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Division is required.');
        }

        // Verify new student belongs to the division
        $student = $this->studentModel
            ->where('id', $studentId)
            ->where('division_id', $divisionId)
            ->where('deleted_at IS NULL', null, false)
            ->first();

        if (!$student) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid student selected.');
        }

        $rules = [
            'student_id'  => 'required|numeric',
            'arrivaltime' => 'required',
            'problem'     => 'required',
            'reason'      => 'required',
            'reduction'   => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->slaModel->update($id, [
            'student_id'  => $studentId,
            'arrivaltime' => $this->request->getPost('arrivaltime'),
            'problem'     => $this->request->getPost('problem'),
            'reason'      => $this->request->getPost('reason'),
            'reduction'   => $this->request->getPost('reduction'),
        ]);

        return redirect()
            ->to('/sla?division=' . $divisionId)
            ->with('success', 'Late arrival slip successfully updated.');
    }


    /**
     * Delete Late Arrival Slip
     */
    public function delete($id)
    {
        $sla = $this->slaModel
            ->select('slas.*, students.division_id')
            ->join(
                'students',
                'students.id = slas.student_id'
            )
            ->where('slas.id', $id)
            ->first();

        if (!$sla) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Late arrival slip not found.'
            );
        }

        $divisionId = $sla['division_id'];

        $this->slaModel->delete($id);

        return redirect()
            ->to('/sla?division=' . $divisionId)
            ->with('success', 'Late arrival slip successfully deleted.');
    }
}