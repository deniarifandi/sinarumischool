<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AcademicYearModel;
use App\Models\SemesterModel;
use App\Models\TermModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\GradebookModel;
use App\Models\UserSubjectModel;
use App\Models\GradebookScoreModel;
use App\Models\StudentModel;
use App\Models\GradeModel;

class GradebookController extends BaseController
{
    protected $userSubjectModel;
    protected $classModel;
    protected $subjectModel;
    protected $academicYearModel;
    protected $semesterModel;
    protected $termModel;
    protected $gradebookModel;
    protected $studentModel;
    protected $gradebookScoreModel;
    protected $gradeModel;

    public function __construct()
    {
        $this->userSubjectModel    = new UserSubjectModel();
        $this->subjectModel        = new SubjectModel();
        $this->classModel          = new ClassModel();
        $this->academicYearModel   = new AcademicYearModel();
        $this->semesterModel       = new SemesterModel();
        $this->termModel           = new TermModel();
        $this->gradebookModel      = new GradebookModel();
        $this->studentModel        = new StudentModel();
        $this->gradebookScoreModel = new GradebookScoreModel();
        $this->gradeModel          = new GradeModel();
    }

    /**
     * Guard: pastikan user yang login memang punya akses ke subject ini.
     * TODO: sempat dinonaktifkan sementara karena isu session/role saat testing.
     * Aktifkan lagi (uncomment pemanggilannya di index()/save()) setelah
     * dipastikan session key & data user_subjects sudah benar.
     */
    private function assertSubjectAccess($subjectId)
    {
        $userId = session()->get('user_id');

        $hasAccess = $this->userSubjectModel
            ->where('user_id', $userId)
            ->where('subject_id', $subjectId)
            ->first();

        if (!$hasAccess) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Anda tidak memiliki akses ke subject ini.');
        }
    }

    public function index()
    {
        $subjectId           = $this->request->getGet('subject_id');
        $classId              = $this->request->getGet('class_id');
        $academicYearIdParam  = $this->request->getGet('academic_year_id');
        $termIdParam          = $this->request->getGet('term_id');

        if (!$subjectId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('subject_id wajib diisi.');
        }

        // $this->assertSubjectAccess($subjectId);

        $subjectDetail = $this->subjectModel->where('id', $subjectId)->get()->getResult();
        if (empty($subjectDetail)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Subject not found');
        }
        $subject = $subjectDetail[0];

        // ============================================================
        // 1. Belum pilih kelas -> tampilkan pilihan Academic Year / Term / Kelas
        // ============================================================
        if (!$classId) {
            $classes = $this->classModel
                ->select('classes.*, grades.grade_name')
                ->join('grades', 'grades.id = classes.grade')
                ->where('grades.division_id', $subject->division_id)
                ->where('grades.deleted_at', null)
                ->orderBy('grades.grade_name')
                ->orderBy('class_name')
                ->findAll();

            // Academic years & terms discope ke divisi subject ini saja
            $academicYears = $this->academicYearModel->getByDivision($subject->division_id);

            $terms = $this->termModel
                ->select('terms.*, semesters.academic_year_id, semesters.name as semester_name')
                ->join('semesters', 'semesters.id = terms.semester_id')
                ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
                ->where('academic_years.division_id', $subject->division_id)
                ->orderBy('terms.start_date', 'DESC')
                ->findAll();

            // Default dropdown ke term aktif divisi ini, kecuali user sudah pilih sendiri
            $activeTerm = $this->termModel->getActiveTerm($subject->division_id);
            $selectedAcademicYearId = $academicYearIdParam ?: ($activeTerm['academic_year_id'] ?? null);
            $selectedTermId         = $termIdParam ?: ($activeTerm['id'] ?? null);

            return view('gradebook/select_class', [
                'classes'                => $classes,
                'subjectId'              => $subjectId,
                'academicYears'          => $academicYears,
                'terms'                  => $terms,
                'selectedAcademicYearId' => $selectedAcademicYearId,
                'selectedTermId'         => $selectedTermId,
                'subjectDetailc'         => $subjectDetail
            ]);
        }

        // ============================================================
        // 2. Kelas sudah dipilih -> tentukan term yang dipakai
        // ============================================================
        if ($termIdParam) {
            $term = $this->termModel->find($termIdParam);
            if (!$term) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Term not found');
            }
        } else {
            $term = $this->termModel->getActiveTerm($subject->division_id);
            if (!$term) {
                return redirect()->back()->with('error', 'Tidak ada term aktif saat ini. Hubungi admin.');
            }
        }

        $termId = $term['id'];

        // academic_year_id & semester_id TIDAK dipercaya dari GET/POST —
        // selalu diturunkan dari term_id lewat semesters, supaya tidak pernah
        // terjadi kombinasi academic_year_id/term_id yang tidak konsisten
        // (ini akar masalah gradebook duplikat yang pernah terjadi di P1A).
        $semester = $this->semesterModel->find($term['semester_id']);
        if (!$semester) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Semester not found');
        }
        $semesterId     = $semester['id'];
        $academicYearId = $semester['academic_year_id'];

        $gradebook = $this->gradebookModel->firstOrCreate([
            
            'term_id'          => $termId,
            'class_id'         => $classId,
            'subject_id'       => $subjectId,
        ]);

        if (!$gradebook) {
            return redirect()->to(base_url('gradebook') . '?subject_id=' . $subjectId)
                ->with('error', 'Term tidak valid atau sudah tidak tersedia.');
        }

        $students = $this->studentModel
            ->where('class_id', $classId)
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();

        $rawScores = $this->gradebookScoreModel
            ->where('gradebook_id', $gradebook['id'])
            ->findAll();

        $scores = [];
        foreach ($rawScores as $row) {
            $scores[$row['student_id']] = $row;
        }

        return view('gradebook/edit', [
            
            'termId'         => $termId,
            'classId'        => $classId,
            'subjectId'      => $subjectId,
            'academicYear'   => $this->academicYearModel->find($academicYearId),
            'semester'       => $semester,
            'term'           => $term,
            'class'          => $this->classModel->find($classId),
            'subject'        => (array) $subject,
            'students'       => $students,
            'scores'         => $scores,
            'isLocked'       => (bool) $gradebook['is_locked'],
        ]);
    }

    public function save()
    {
        $req = $this->request;

        $classId    = $req->getPost('class_id');
        $subjectId  = $req->getPost('subject_id');
        $termIdPost = $req->getPost('term_id');
        $studentIds = $req->getPost('student_id') ?? [];

        // $this->assertSubjectAccess($subjectId);

        if (!$termIdPost) {
            session()->setFlashdata('error', 'Term tidak valid.');
            return redirect()->to(base_url('gradebook') . '?subject_id=' . $subjectId);
        }

        // Sama seperti index(): academic_year_id & semester_id diturunkan
        // dari term_id di server, BUKAN dipercaya dari hidden input form.
        $term = $this->termModel->find($termIdPost);
        if (!$term) {
            session()->setFlashdata('error', 'Term tidak ditemukan.');
            return redirect()->to(base_url('gradebook') . '?subject_id=' . $subjectId);
        }

        $semester = $this->semesterModel->find($term['semester_id']);
        if (!$semester) {
            session()->setFlashdata('error', 'Semester tidak ditemukan.');
            return redirect()->to(base_url('gradebook') . '?subject_id=' . $subjectId);
        }

        $termId         = $term['id'];
        $semesterId     = $semester['id'];
        

        $backUrl = base_url('gradebook') . '?' . http_build_query([
            'subject_id'       => $subjectId,
            'class_id'         => $classId,
            
            'term_id'          => $termId,
        ]);

        $gradebook = $this->gradebookModel->firstOrCreate([
          
            'term_id'          => $termId,
            'class_id'         => $classId,
            'subject_id'       => $subjectId,
        ]);

        if (!$gradebook) {
            session()->setFlashdata('error', 'Term tidak valid, nilai tidak tersimpan.');
            return redirect()->to(base_url('gradebook') . '?subject_id=' . $subjectId);
        }

        if ($gradebook['is_locked']) {
            session()->setFlashdata('error', 'Gradebook ini sudah terkunci.');
            return redirect()->to($backUrl);
        }

        $fields = ['ct1', 'ct1_remedial', 'ct2', 'ct2_remedial', 'individual_project', 'group_project'];

        $posted = [];
        foreach ($fields as $f) {
            $posted[$f] = $req->getPost($f) ?? [];
        }

        // 1. Validasi dulu - reject-all kalau ada yang invalid
        $errors = [];
        $studentNames = $this->studentModel->whereIn('id', $studentIds)->findAll();
        $nameMap = array_column($studentNames, 'name', 'id');

        foreach ($studentIds as $studentId) {
            foreach ($fields as $f) {
                $raw = trim($posted[$f][$studentId] ?? '');
                if ($raw === '' || $raw === '-') continue; // kosong = boleh

                $normalized = str_replace(',', '.', $raw);
                if (!is_numeric($normalized) || $normalized < 0 || $normalized > 100) {
                    $errors[] = ($nameMap[$studentId] ?? "ID {$studentId}") . " - " . strtoupper(str_replace('_', ' ', $f)) . ": \"{$raw}\"";
                }
            }
        }

        if (!empty($errors)) {
            session()->setFlashdata('error', 'Beberapa nilai tidak valid (0-100), tidak ada yang tersimpan.');
            session()->setFlashdata('validation_errors', $errors);
            session()->setFlashdata('old_input', $req->getPost());
            return redirect()->to($backUrl);
        }

        // 2. Semua valid -> simpan
        $data = [];
        foreach ($studentIds as $studentId) {
            $row = ['gradebook_id' => $gradebook['id'], 'student_id' => $studentId];
            foreach ($fields as $f) {
                $raw = trim($posted[$f][$studentId] ?? '');
                $row[$f] = ($raw === '' || $raw === '-') ? null : str_replace(',', '.', $raw);
            }
            $data[] = $row;
        }

        $this->gradebookScoreModel->upsertBatch($data);

        session()->setFlashdata('success', 'Nilai berhasil disimpan.');
        return redirect()->to($backUrl);
    }

    public function curriculum()
{
    $divisionId        = $this->request->getGet('division');
    $classId        = $this->request->getGet('class_id');
    $academicYearId = $this->request->getGet('academic_year_id');
    $termId         = $this->request->getGet('term_id');

    // ============================================================
    // STEP 1: BELUM PILIH CLASS
    // Show Academic Year / Term / Grade / Class selection
    // ============================================================
    if (!$classId) {

        // Semua classes
        $classes = $this->classModel
            ->select('classes.*, grades.grade_name, grades.division_id')
            ->join('grades', 'grades.id = classes.grade')
            ->where('grades.deleted_at', null)
            ->where('classes.division_id',$divisionId)
            ->orderBy('grades.grade_name')
            ->orderBy('classes.class_name')
            ->findAll();

        // Semua academic years
        $academicYears = $this->academicYearModel
            ->where('academic_years.division_id',$divisionId)
            ->orderBy('start_date', 'DESC')
            ->findAll();

        // Semua terms + academic year
        $terms = $this->termModel
            ->select('
                terms.*,
                semesters.academic_year_id,
                semesters.name as semester_name
            ')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->orderBy('terms.start_date', 'DESC')
            ->findAll();

        return view('gradebook/curriculum_select', [
            'classes'                => $classes,
            'academicYears'          => $academicYears,
            'terms'                  => $terms,
            'selectedAcademicYearId' => $academicYearId,
            'selectedTermId'         => $termId,
        ]);
    }

    // ============================================================
    // STEP 2: CLASS SUDAH DIPILIH
    // Show all subjects + grades
    // ============================================================

    if (!$termId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'term_id wajib diisi.'
        );
    }

    // Class
    $class = $this->classModel
        ->select('classes.*, grades.grade_name, grades.division_id')
        ->join('grades', 'grades.id = classes.grade')
        ->where('classes.id', $classId)
        ->first();

    if (!$class) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Class not found.'
        );
    }

    // Term
    $term = $this->termModel->find($termId);

    if (!$term) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Term not found.'
        );
    }

    // Semester
    $semester = $this->semesterModel->find($term['semester_id']);

    if (!$semester) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Semester not found.'
        );
    }

    // Academic year derived from term
    $academicYearId = $semester['academic_year_id'];

    $academicYear = $this->academicYearModel->find($academicYearId);

    // ============================================================
    // STUDENTS
    // ============================================================

    $students = $this->studentModel
        ->where('class_id', $classId)
        ->where('deleted_at', null)
        ->orderBy('name', 'ASC')
        ->findAll();

    // ============================================================
    // SUBJECTS
    // ============================================================

    $subjects = $this->subjectModel
        ->where('division_id', $class['division_id'])
        ->orderBy('subject_name', 'ASC')
        ->findAll();

    // ============================================================
    // GRADEBOOKS
    // ============================================================

    $gradebooks = $this->gradebookModel
        ->where('class_id', $classId)
        ->where('term_id', $termId)
        ->findAll();

    $gradebookMap = [];

    foreach ($gradebooks as $gradebook) {
        $gradebookMap[$gradebook['subject_id']] = $gradebook;
    }

    // ============================================================
    // SCORES
    // ============================================================

    $scores = [];

    if (!empty($gradebooks)) {

        $gradebookIds = array_column($gradebooks, 'id');

        $rawScores = $this->gradebookScoreModel
            ->whereIn('gradebook_id', $gradebookIds)
            ->findAll();

        foreach ($rawScores as $score) {

            $scores[$score['gradebook_id']][$score['student_id']] = $score;
        }
    }

    // ============================================================
    // SUBJECT + SCORE DATA
    // ============================================================

    $subjectGrades = [];

    foreach ($subjects as $subject) {

        $gradebook = $gradebookMap[$subject['id']] ?? null;

        $subjectGrades[] = [
            'subject'   => $subject,
            'gradebook' => $gradebook,
            'scores'    => $gradebook
                ? ($scores[$gradebook['id']] ?? [])
                : [],
        ];
    }

    return view('gradebook/curriculum', [
        'class'          => $class,
        'students'       => $students,
        'subjects'       => $subjectGrades,
        'term'           => $term,
        'semester'       => $semester,
        'academicYear'   => $academicYear,
        'classId'        => $classId,
        'termId'         => $termId,
        'academicYearId' => $academicYearId,
    ]);
}

private function curriculumGradeReport($classId, $academicYearId, $termId)
{
    // ============================================================
    // Validate Class
    // ============================================================

    $class = $this->classModel
        ->select('
            classes.*,
            grades.grade_name
        ')
        ->join('grades', 'grades.id = classes.grade')
        ->where('classes.id', $classId)
        ->first();

    if (!$class) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Class not found.'
        );
    }

    // ============================================================
    // Validate Academic Year
    // ============================================================

    $academicYear = $this->academicYearModel->find($academicYearId);

    if (!$academicYear) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Academic year not found.'
        );
    }

    // ============================================================
    // Validate Term
    // ============================================================

    $term = $this->termModel->find($termId);

    if (!$term) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Term not found.'
        );
    }

    // Make sure term actually belongs to selected academic year
    $semester = $this->semesterModel->find($term['semester_id']);

    if (!$semester || $semester['academic_year_id'] != $academicYearId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Term does not belong to this academic year.'
        );
    }

    // ============================================================
    // Students
    // ============================================================

    $students = $this->studentModel
        ->where('class_id', $classId)
        ->where('deleted_at', null)
        ->orderBy('name', 'ASC')
        ->findAll();

    // ============================================================
    // Subjects
    // ============================================================

    $subjects = $this->subjectModel
        ->orderBy('subject_name', 'ASC')
        ->findAll();

    // ============================================================
    // Gradebooks
    // ============================================================

    $gradebooks = $this->gradebookModel
        ->where('term_id', $termId)
        ->where('class_id', $classId)
        ->findAll();

    // Map:
    // [subject_id] => gradebook
    $gradebookMap = [];

    foreach ($gradebooks as $gradebook) {
        $gradebookMap[$gradebook['subject_id']] = $gradebook;
    }

    // ============================================================
    // Scores
    // ============================================================

    $scores = [];

    if (!empty($gradebooks)) {

        $gradebookIds = array_column($gradebooks, 'id');

        $rawScores = $this->gradebookScoreModel
            ->whereIn('gradebook_id', $gradebookIds)
            ->findAll();

        foreach ($rawScores as $score) {
            $scores[$score['student_id']][$score['gradebook_id']] = $score;
        }
    }

    // ============================================================
    // Build final report
    // ============================================================

    $report = [];

    foreach ($students as $student) {

        $studentId = $student['id'];

        $report[$studentId] = [
            'student' => $student,
            'subjects' => []
        ];

        foreach ($subjects as $subject) {

            $subjectId = $subject['id'];

            $gradebook = $gradebookMap[$subjectId] ?? null;

            $score = null;

            if ($gradebook) {
                $score = $scores[$studentId][$gradebook['id']] ?? null;
            }

            $report[$studentId]['subjects'][$subjectId] = [
                'subject' => $subject,
                'score'   => $score
            ];
        }
    }

    return view('curriculum/grade_report', [
        'class'          => $class,
        'academicYear'   => $academicYear,
        'semester'       => $semester,
        'term'           => $term,
        'students'       => $students,
        'subjects'       => $subjects,
        'gradebooks'     => $gradebookMap,
        'scores'         => $scores,
        'report'         => $report,
    ]);
}
}