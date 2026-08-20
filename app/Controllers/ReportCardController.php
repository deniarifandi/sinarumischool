<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\TermModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\GradebookModel;
use App\Models\GradebookScoreModel;

class ReportCardController extends BaseController
{
     protected $academicYearModel;
    protected $semesterModel;
    protected $termModel;
    protected $classModel;
    protected $subjectModel;
    protected $studentModel;
    protected $gradebookModel;
    protected $gradebookScoreModel;

    public function __construct()
    {
        $this->academicYearModel   = new AcademicYearModel();
        $this->semesterModel       = new SemesterModel();
        $this->termModel           = new TermModel();
        $this->classModel          = new ClassModel();
        $this->subjectModel        = new SubjectModel();
        $this->studentModel        = new StudentModel();
        $this->gradebookModel      = new GradebookModel();
        $this->gradebookScoreModel = new GradebookScoreModel();
    }

   public function class($classId)
{
    $class = $this->classModel
        ->select('classes.*, grades.grade_name')
        ->join('grades', 'grades.id = classes.grade')
        ->where('classes.id', $classId)
        ->first();

    if (!$class) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
    }

    $students = $this->studentModel
        ->where('class_id', $classId)
        ->where('deleted_at', null)
        ->orderBy('name', 'ASC')
        ->findAll();

    $academicYears = $this->academicYearModel
        ->orderBy('start_date', 'DESC')
        ->findAll();

    $terms = $this->termModel
        ->select('terms.*, semesters.academic_year_id, semesters.name as semester_name')
        ->join('semesters', 'semesters.id = terms.semester_id')
        ->orderBy('terms.start_date', 'DESC')
        ->findAll();

    return view('report_card/students', [
        'class'        => $class,
        'students'     => $students,
        'academicYears'=> $academicYears,
        'terms'        => $terms,
    ]);
}

    public function student($studentId)
{
    $classId        = $this->request->getGet('class_id');
    $academicYearId = $this->request->getGet('academic_year_id');
    $termId         = $this->request->getGet('term_id');

    if (!$studentId || !$classId || !$termId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Student, class_id dan term_id wajib diisi.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Student
    |--------------------------------------------------------------------------
    */

    $student = $this->studentModel
        ->where('id', $studentId)
        ->where('deleted_at', null)
        ->first();

    if (!$student) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Student tidak ditemukan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 2. Class
    |--------------------------------------------------------------------------
    */

    $class = $this->classModel
        ->select('classes.*, grades.grade_name, grades.division_id')
        ->join('grades', 'grades.id = classes.grade')
        ->where('classes.id', $classId)
        ->first();

    if (!$class) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Class tidak ditemukan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. Verify student belongs to this class
    |--------------------------------------------------------------------------
    */

    if ((int) $student['class_id'] !== (int) $classId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Student tidak berada di class ini.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. Term
    |--------------------------------------------------------------------------
    */

    $term = $this->termModel->find($termId);

    if (!$term) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Term tidak ditemukan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 5. Semester
    |--------------------------------------------------------------------------
    */

    $semester = $this->semesterModel->find($term['semester_id']);

    if (!$semester) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Semester tidak ditemukan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 6. Academic Year
    |--------------------------------------------------------------------------
    |
    | Do not trust academic_year_id from URL.
    | Derive it from the semester belonging to the term.
    |
    */

    $actualAcademicYearId = $semester['academic_year_id'];

    $academicYear = $this->academicYearModel
        ->find($actualAcademicYearId);

    if (!$academicYear) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Academic year tidak ditemukan.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 7. Get subjects
    |--------------------------------------------------------------------------
    |
    | Subjects are determined by the student's class division.
    |
    */

    $subjects = $this->subjectModel
        ->where('division_id', $class['division_id'])
        ->where('deleted_at', null)
        ->orderBy('subject_name', 'ASC')
        ->findAll();


    /*
    |--------------------------------------------------------------------------
    | 8. Get Gradebooks for this class + term
    |--------------------------------------------------------------------------
    |
    | One gradebook exists for:
    |
    | term + class + subject
    |
    */

    $gradebooks = $this->gradebookModel
        ->where('term_id', $termId)
        ->where('class_id', $classId)
        ->findAll();


    /*
    |--------------------------------------------------------------------------
    | 9. Map gradebook by subject_id
    |--------------------------------------------------------------------------
    */

    $gradebookMap = [];

    foreach ($gradebooks as $gradebook) {
        $gradebookMap[$gradebook['subject_id']] = $gradebook;
    }


    /*
    |--------------------------------------------------------------------------
    | 10. Get student's scores
    |--------------------------------------------------------------------------
    */

    $scores = [];

    // Get all gradebook IDs
    $gradebookIds = array_column($gradebooks, 'id');

    $scoreRows = [];

    if (!empty($gradebookIds)) {
        $scoreRows = $this->gradebookScoreModel
            ->whereIn('gradebook_id', $gradebookIds)
            ->where('student_id', $studentId)
            ->findAll();
    }

    // Map scores by gradebook_id
    $scoreMap = [];

    foreach ($scoreRows as $row) {
        $scoreMap[$row['gradebook_id']] = $row;
    }

    // Build score data for EVERY subject
    foreach ($subjects as $subject) {

        $subjectId = $subject['id'];

        // Default: no grade
        $scores[$subjectId] = [
            'ct1'               => '-',
            'ct1_remedial'      => '-',
            'ct2'               => '-',
            'ct2_remedial'      => '-',
            'individual_project'=> '-',
            'group_project'     => '-',
        ];

        // Does this subject have a gradebook?
        if (isset($gradebookMap[$subjectId])) {

            $gradebookId = $gradebookMap[$subjectId]['id'];

            // Does this student have a score?
            if (isset($scoreMap[$gradebookId])) {

                $row = $scoreMap[$gradebookId];

                $scores[$subjectId] = [
                    'ct1'                => $row['ct1'] ?? '-',
                    'ct1_remedial'       => $row['ct1_remedial'] ?? '-',
                    'ct2'                => $row['ct2'] ?? '-',
                    'ct2_remedial'       => $row['ct2_remedial'] ?? '-',
                    'individual_project' => $row['individual_project'] ?? '-',
                    'group_project'      => $row['group_project'] ?? '-',
                ];
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 11. Attendance
    |--------------------------------------------------------------------------
    |
    | Adjust this section if your attendance table/model uses
    | different fields.
    |
    */

    $attendance = [
        'sickness'       => 0,
        'authorized'     => 0,
        'unauthorized'   => 0,
        'total_meetings' => 0,
    ];


    /*
    |--------------------------------------------------------------------------
    | 12. Teacher / class teacher
    |--------------------------------------------------------------------------
    |
    | For now we try to get the class teacher from the class record.
    | If your classes table uses a different field, adjust this part.
    |
    */

    $teacher = [
        'name' => ''
    ];

    if (!empty($class['teacher_id'])) {

        $userModel = new \App\Models\UserModel();

        $teacherData = $userModel->find($class['teacher_id']);

        if ($teacherData) {
            $teacher['name'] = $teacherData['name'] ?? '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 13. Render detail.php
    |--------------------------------------------------------------------------
    */

    return view('report_card/detail', [

        'student'      => $student,

        'class'        => $class,

        'academicYear' => $academicYear,

        'semester'     => $semester,

        'term'         => $term,

        'subjects'     => $subjects,

        'scores'       => $scores,

        'attendance'   => $attendance,

        'teacher'      => $teacher,

        'gradebooks'   => $gradebookMap,

    ]);
}
}