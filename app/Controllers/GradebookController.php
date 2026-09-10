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
use App\Models\GradebookObjectiveScoreModel;
use App\Models\OutcomeModel;
use App\Models\ObjectiveModel;
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
        protected $gradebookObjectiveScoreModel;
        protected $outcomeModel;
        protected $objectiveModel;
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
            $this->gradebookObjectiveScoreModel = new GradebookObjectiveScoreModel();
            $this->outcomeModel        = new OutcomeModel();
            $this->objectiveModel      = new ObjectiveModel();
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
    $subjectId          = $this->request->getGet('subject_id');
    $classId            = $this->request->getGet('class_id');
    $academicYearIdParam = $this->request->getGet('academic_year_id');
    $termIdParam        = $this->request->getGet('term_id');

    if (!$subjectId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'subject_id wajib diisi.'
        );
    }

    // ============================================================
    // SUBJECT
    // ============================================================

    $subjectDetail = $this->subjectModel
        ->where('id', $subjectId)
        ->get()
        ->getResult();

    if (empty($subjectDetail)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Subject not found'
        );
    }

    $subject = $subjectDetail[0];

    // ============================================================
    // 1. BELUM PILIH CLASS
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

        $academicYears = $this->academicYearModel
            ->getByDivision($subject->division_id);

        $terms = $this->termModel
            ->select('
                terms.*,
                semesters.academic_year_id,
                semesters.name as semester_name
            ')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
            ->where('academic_years.division_id', $subject->division_id)
            ->orderBy('terms.start_date', 'DESC')
            ->findAll();

        $activeTerm = $this->termModel
            ->getActiveTerm($subject->division_id);

        $selectedAcademicYearId =
            $academicYearIdParam
            ?: ($activeTerm['academic_year_id'] ?? null);

        $selectedTermId =
            $termIdParam
            ?: ($activeTerm['id'] ?? null);

        return view('gradebook/select_class', [
            'classes'                => $classes,
            'subjectId'              => $subjectId,
            'academicYears'          => $academicYears,
            'terms'                  => $terms,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedTermId'         => $selectedTermId,
            'subjectDetailc'         => $subjectDetail,
        ]);
    }

    // ============================================================
    // 2. DETERMINE TERM
    // ============================================================

    if ($termIdParam) {

        $term = $this->termModel->find($termIdParam);

        if (!$term) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                'Term not found'
            );
        }

    } else {

        $term = $this->termModel
            ->getActiveTerm($subject->division_id);

        if (!$term) {
            return redirect()->back()->with(
                'error',
                'Tidak ada term aktif saat ini. Hubungi admin.'
            );
        }
    }

    $termId = $term['id'];

    // ============================================================
    // 3. SEMESTER
    // ============================================================

    $semester = $this->semesterModel
        ->find($term['semester_id']);

    if (!$semester) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Semester not found'
        );
    }

    $semesterId     = $semester['id'];
    $academicYearId = $semester['academic_year_id'];

    // ============================================================
    // 4. CLASS
    // ============================================================

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

    // Optional but recommended:
    // make sure class belongs to the same division as subject
    if ((int) $class['division_id'] !== (int) $subject->division_id) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Subject and class division do not match.'
        );
    }

    // ============================================================
    // 5. ACADEMIC YEAR
    // ============================================================

    $academicYear = $this->academicYearModel
        ->find($academicYearId);

    if (!$academicYear) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Academic year not found.'
        );
    }

    // ============================================================
    // 6. GET / CREATE GRADEBOOK
    // ============================================================

    $gradebook = $this->gradebookModel->firstOrCreate([
        'term_id'    => $termId,
        'class_id'   => $classId,
        'subject_id' => $subjectId,
    ]);

    if (!$gradebook) {
        return redirect()
            ->to(base_url('gradebook') . '?' . http_build_query([
                'subject_id' => $subjectId,
            ]))
            ->with(
                'error',
                'Term tidak valid atau sudah tidak tersedia.'
            );
    }

    // ============================================================
    // 7. STUDENTS
    // ============================================================

    $students = $this->studentModel
        ->where('class_id', $classId)
        ->where('deleted_at', null)
        ->orderBy('name', 'ASC')
        ->findAll();

    // ============================================================
    // 8. SCORES
    // ============================================================

    $rawScores = $this->gradebookScoreModel
        ->where('gradebook_id', $gradebook['id'])
        ->findAll();

    $scores = [];

    foreach ($rawScores as $row) {
        $scores[$row['student_id']] = $row;
    }

    // ============================================================
    // 9. DETECT RELIGION SUBJECT
    // ============================================================
    //
    // Examples:
    //
    // Religion : Buddhist
    // Religion : Catholic
    // Religion : Christian
    // Religion : Hindu
    // Religion : Islam
    //
    // $religionSubject will contain the normalized religion.
    //
    // For normal subjects:
    // $religionSubject = null
    //

    $subjectName = trim($subject->subject_name ?? '');

    $religionSubject = null;

    if (preg_match(
        '/^religion\s*:\s*(buddhist|buddha|catholic|katolik|christian|kristen|hindu|islam)\s*$/i',
        $subjectName,
        $matches
    )) {
        $religionSubject = strtolower(trim($matches[1]));

        // Normalize database variations
        $religionMap = [
            'islam'     => 'islam',
            'christian' => 'christian',
            'kristen'   => 'christian',
            'catholic'  => 'catholic',
            'katolik'   => 'catholic',
            'hindu'     => 'hindu',
            'buddhist'  => 'buddhist',
            'buddha'    => 'buddhist',
        ];

        $religionSubject = $religionMap[$religionSubject]
            ?? $religionSubject;
    }

    // ============================================================
    // 10. ADD RELIGION MATCH STATUS TO STUDENTS
    // ============================================================

    foreach ($students as &$student) {

        $studentReligion = strtolower(
            trim($student['murid_agama'] ?? '')
        );

        // Normalize student's religion
        $studentReligionMap = [
            'islam'     => 'islam',
            'christian' => 'christian',
            'kristen'   => 'christian',
            'catholic'  => 'catholic',
            'katolik'   => 'catholic',
            'hindu'     => 'hindu',
            'buddhist'  => 'buddhist',
            'buddha'    => 'buddhist',
        ];

        $studentReligion = $studentReligionMap[$studentReligion]
            ?? $studentReligion;

        if ($religionSubject !== null) {

            $student['religion_match'] =
                ($studentReligion === $religionSubject);

        } else {

            // Normal subject: everybody can edit
            $student['religion_match'] = true;
        }
    }

    unset($student);

        // ============================================================
        // 11. OBJECTIVE-BASED (TAB 2) DATA
        // ============================================================
        //
        // Kolom objektif AUTO-generate dari objectives:
        //   - subject  : via outcome.subject_id
        //   - term     : objective.term_id == term gradebook (NULL = tidak muncul)
        // Guru tidak perlu memilih/menambah kolom lagi.

        $objectives = $this->objectiveModel
            ->select(
                'objectives.id as objective_id,
                 objectives.objective_name,
                 outcomes.id as outcome_id,
                 outcomes.outcome_name'
            )
            ->join('outcomes', 'outcomes.id = objectives.outcome_id')
            ->join('terms', 'terms.id = objectives.term_id')
            ->where('outcomes.subject_id', $subjectId)
            ->where('outcomes.grade_id', $class['grade'])
            ->where('terms.name', $term['name'])
            ->orderBy('outcomes.outcome_name', 'ASC')
            ->orderBy('objectives.objective_name', 'ASC')
            ->findAll();

        $objectiveScores = $this->gradebookObjectiveScoreModel
            ->getByGradebook($gradebook['id']);

        // Outcomes untuk quick-add objective (subject + grade deze klas)
        $outcomes = $this->outcomeModel
            ->where('subject_id', $subjectId)
            ->where('grade_id', $class['grade'])
            ->orderBy('outcome_name', 'ASC')
            ->findAll();

        // ============================================================
        // 12. RETURN VIEW
        // ============================================================

        return view('gradebook/edit', [

            'termId'         => $termId,
            'classId'        => $classId,
            'subjectId'      => $subjectId,

            'gradebookId'    => $gradebook['id'],

            'academicYear'   => $academicYear,
            'semester'       => $semester,
            'term'           => $term,
            'class'          => $class,

            'subject'        => (array) $subject,

            'students'       => $students,
            'scores'         => $scores,

            // Objective-based (TAB 2) — kolom AUTO dari objectives (term + subject)
            'objectives'        => $objectives,
            'objectiveScores'   => $objectiveScores,
            'outcomes'          => $outcomes,

            'isLocked'       => (bool) $gradebook['is_locked'],

            // Important for the view
            'religionSubject' => $religionSubject,
        ]);
    }

    public function indexOLD()
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

        /*
        |--------------------------------------------------------------------------
        | Religion Subject Filter
        |--------------------------------------------------------------------------
        |
        | If the subject is:
        |   Religion : Islam
        |   Religion : Christian
        |   Religion : Catholic
        |   Religion : Buddhist
        |   Religion : Hindu
        |
        | Only students with the corresponding religion are shown.
        |
        */

        $subjectName = trim($subject->subject_name ?? '');

        if (preg_match('/^Religion\s*:\s*(.+)$/i', $subjectName, $matches)) {

            $subjectReligion = strtolower(trim($matches[1]));

            // Normalize religion names
            $religionMap = [
                'islam'     => 'islam',

                'christian' => 'christian',
                'kristen'   => 'christian',

                'catholic'  => 'catholic',
                'katolik'   => 'catholic',

                'buddhist'  => 'buddhist',
                'buddha'    => 'buddhist',
                'budha'     => 'buddhist',

                'hindu'     => 'hindu',
            ];

            $subjectReligion = $religionMap[$subjectReligion] ?? null;

            if ($subjectReligion) {

                $students = array_values(array_filter(
                    $students,
                    function ($student) use ($subjectReligion, $religionMap) {

                        $studentReligion = strtolower(
                            trim($student['murid_agama'] ?? '')
                        );

                        $studentReligion = $religionMap[$studentReligion] ?? null;

                        return $studentReligion === $subjectReligion;
                    }
                ));
            }
        }

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

    // ============================================================
    // OBJECTIVE-BASED GRADEBOOK (TAB 2)
    // ============================================================

    /**
     * Helper: back-url gradebook
     */
    private function objectiveBackUrl($subjectId, $classId, $termId)
    {
        return base_url('gradebook') . '?' . http_build_query([
            'subject_id' => $subjectId,
            'class_id'   => $classId,
            'term_id'    => $termId,
        ]);
    }

    /**
     * Simpan score objektif semua student per kolom (per objective_id).
     * Kolom objektif AUTO dari objectives, jadi cukup score[objective_id][student_id].
     * OPT: POST gradebook_id, subject_id, class_id, term_id,
     *      student_id[], objective_id[], score[objective_id][student_id]
     */
    public function saveObjective()
    {
        $req = $this->request;

        $gradebookId = (int) $req->getPost('gradebook_id');
        $subjectId   = $req->getPost('subject_id');
        $classId     = $req->getPost('class_id');
        $termId      = $req->getPost('term_id');

        $studentIds = $req->getPost('student_id') ?? [];

        // Kolom objektif yang dirender di form (per objektif)
        $objectiveIds = $req->getPost('objective_id') ?? [];

        $backUrl = $this->objectiveBackUrl($subjectId, $classId, $termId);

        $gradebook = $this->gradebookModel->find($gradebookId);

        if (!$gradebook) {
            session()->setFlashdata('error', 'Gradebook tidak ditemukan.');
            return redirect()->to($backUrl);
        }

        $term = $this->termModel->find($termId);
        if (!$term) {
            session()->setFlashdata('error', 'Term tidak ditemukan.');
            return redirect()->to($backUrl);
        }

        if ($gradebook['is_locked']) {
            session()->setFlashdata('error', 'Gradebook ini sudah terkunci.');
            return redirect()->to($backUrl);
        }

        // Validasi: hanya objektif yang memang milik subject+term ini
        // (sama dengan aturan auto-generate kolom).
        $validObjectives = [];

        $objectives = $this->objectiveModel
            ->select('objectives.id')
            ->join('outcomes', 'outcomes.id = objectives.outcome_id')
            ->join('terms', 'terms.id = objectives.term_id')
            ->where('outcomes.subject_id', $subjectId)
            ->where('terms.name', $term['name'])
            ->whereIn('objectives.id', $objectiveIds)
            ->findAll();

        foreach ($objectives as $o) {
            $validObjectives[(int) $o['id']] = true;
        }

        $posted    = $req->getPost('score') ?? [];

        $studentNames = $this->studentModel->whereIn('id', $studentIds)->findAll();
        $nameMap      = array_column($studentNames, 'name', 'id');

        // ---- Validate 0-100 all columns x students ----
        $errors = [];

        foreach ($objectiveIds as $oid) {

            if (!isset($validObjectives[$oid])) {
                continue;
            }

            $postedCol = $posted[$oid] ?? [];

            foreach ($studentIds as $studentId) {

                $raw = trim($postedCol[$studentId] ?? '');

                if ($raw === '' || $raw === '-') {
                    continue;
                }

                $normalized = str_replace(',', '.', $raw);

                if (!is_numeric($normalized) || $normalized < 0 || $normalized > 100) {

                    $errors[] = ($nameMap[$studentId] ?? "ID {$studentId}")
                        . " - \"{$raw}\"";
                }
            }
        }

        if (!empty($errors)) {
            session()->setFlashdata('error', 'Beberapa nilai tidak valid (0-100), tidak ada yang tersimpan.');
            session()->setFlashdata('validation_errors', $errors);
            session()->setFlashdata('old_input', $req->getPost());
            return redirect()->to($backUrl);
        }

        // ---- Save per kolom objektif ----
        foreach ($objectiveIds as $oid) {

            if (!$validObjectives[$oid]) {
                continue;
            }

            $postedCol = $posted[$oid] ?? [];

            $data = [];

            foreach ($studentIds as $studentId) {

                $raw = trim($postedCol[$studentId] ?? '');

                $data[] = [
                    'gradebook_id' => $gradebookId,
                    'objective_id' => (int) $oid,
                    'student_id'   => $studentId,
                    'score'        => ($raw === '' || $raw === '-')
                        ? null
                        : str_replace(',', '.', $raw),
                ];
            }

            if (!empty($data)) {
                $this->gradebookObjectiveScoreModel->upsertBatch($data);
            }
        }

        session()->setFlashdata('success', 'Nilai objektif berhasil disimpan.');
        return redirect()->to($backUrl);
    }


    /**
     * Quick-add objective rechtstreeks vanuit gradebook (TAB 2).
     * POST: gradebook_id, subject_id, class_id, term_id, outcome_id, objective_name
     *
     * Huidige term_id van de gradebook wordt aan objective.term_id toegewezen,
     * zodat de nieuwe kolomm je direct verschijnt in de objective-tab.
     */
    public function addObjective()
    {
        $req = $this->request;

        $subjectId      = $req->getPost('subject_id');
        $classId        = $req->getPost('class_id');
        $termId         = (int) $req->getPost('term_id');
        $gradebookId    = (int) $req->getPost('gradebook_id');
        $outcomeId      = (int) $req->getPost('outcome_id');
        $objectiveName  = trim($req->getPost('objective_name') ?? '');

        $backUrl = $this->objectiveBackUrl($subjectId, $classId, $termId);

        if ($objectiveName === '' || !$outcomeId) {
            session()->setFlashdata('error', 'Objective name en Outcome zijn verplicht.');
            return redirect()->to($backUrl);
        }

        $gradebook = $this->gradebookModel->find($gradebookId);

        if (!$gradebook) {
            session()->setFlashdata('error', 'Gradebook niet gevonden.');
            return redirect()->to($backUrl);
        }

        if ($gradebook['is_locked']) {
            session()->setFlashdata('error', 'Gradebook is vergrendeld, objective kan niet worden toegevoegd.');
            return redirect()->to($backUrl);
        }

        $class = $this->classModel->find($classId);

        if (!$class) {
            session()->setFlashdata('error', 'Klas niet gevonden.');
            return redirect()->to($backUrl);
        }

        // Waarborg dat outcome bij subject + grade van deze klas hoort
        $outcome = $this->outcomeModel
            ->where('id', $outcomeId)
            ->where('subject_id', $subjectId)
            ->where('grade_id', $class['grade'])
            ->first();

        if (!$outcome) {
            session()->setFlashdata('error', 'Outcome niet geldig voor dit subject/klas.');
            return redirect()->to($backUrl);
        }

        $this->objectiveModel->insert([
            'outcome_id'     => $outcomeId,
            'term_id'        => $termId,
            'objective_name' => $objectiveName,
        ]);

        session()->setFlashdata('success', 'Objective "' . $objectiveName . '" succesvol toegevoegd.');
        return redirect()->to($backUrl);
    }

    /**
     * Hapus objective dari gradebook (hapus kolom beserta semua nilai siswa).
     * POST: gradebook_id, subject_id, class_id, term_id, objective_id
     * Hanya boleh hapus kalau gradebook belum terkunci.
     */
    public function deleteObjective()
    {
        $req = $this->request;

        $subjectId      = $req->getPost('subject_id');
        $classId        = $req->getPost('class_id');
        $termId         = (int) $req->getPost('term_id');
        $gradebookId    = (int) $req->getPost('gradebook_id');
        $objectiveId    = (int) $req->getPost('objective_id');

        $backUrl = $this->objectiveBackUrl($subjectId, $classId, $termId);

        if (!$objectiveId) {
            session()->setFlashdata('error', 'Objective tidak ditemukan.');
            return redirect()->to($backUrl);
        }

        $gradebook = $this->gradebookModel->find($gradebookId);

        if (!$gradebook) {
            session()->setFlashdata('error', 'Gradebook tidak ditemukan.');
            return redirect()->to($backUrl);
        }

        if ($gradebook['is_locked']) {
            session()->setFlashdata('error', 'Gradebook terkunci, tidak bisa menghapus objective.');
            return redirect()->to($backUrl);
        }

        $this->objectiveModel->delete($objectiveId);

        session()->setFlashdata('success', 'Objective berhasil dihapus.');
        return redirect()->to($backUrl);
    }


    public function curriculum()
    {
    $divisionId    = $this->request->getGet('division');
    $classId      = $this->request->getGet('class_id');
    $academicYearId = $this->request->getGet('academic_year_id');
    $termId       = $this->request->getGet('term_id');

    // ============================================================
    // STEP 1: BELUM PILIH CLASS
    // Show Academic Year / Term / Grade / Class selection
    // ============================================================

    if (!$classId) {

        // Classes
        $classes = $this->classModel
            ->select('classes.*, grades.grade_name, grades.division_id')
            ->join('grades', 'grades.id = classes.grade')
            ->where('grades.deleted_at', null)
            ->where('classes.division_id', $divisionId)
            ->orderBy('grades.grade_name')
            ->orderBy('classes.class_name')
            ->findAll();

        // Academic Years
        $academicYears = $this->academicYearModel
            ->where('academic_years.division_id', $divisionId)
            ->orderBy('start_date', 'DESC')
            ->findAll();

        // Terms + Academic Year
        $terms = $this->termModel
            ->select('
                terms.*,
                semesters.academic_year_id,
                semesters.name as semester_name
            ')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->join(
                'academic_years',
                'academic_years.id = semesters.academic_year_id'
            )
            ->where('academic_years.division_id', $divisionId)
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
    // ============================================================

    if (!$termId) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'term_id wajib diisi.'
        );
    }


    // ============================================================
    // CLASS
    // ============================================================

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


    // ============================================================
    // TERM
    // ============================================================

    $term = $this->termModel->find($termId);

    if (!$term) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Term not found.'
        );
    }


    // ============================================================
    // SEMESTER
    // ============================================================

    $semester = $this->semesterModel->find($term['semester_id']);

    if (!$semester) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Semester not found.'
        );
    }


    // ============================================================
    // ACADEMIC YEAR
    // Always derive from semester
    // ============================================================

    $academicYearId = $semester['academic_year_id'];

    $academicYear = $this->academicYearModel->find($academicYearId);

    if (!$academicYear) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException(
            'Academic Year not found.'
        );
    }


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
        ->where('deleted_at', null)
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


    // ============================================================
    // RETURN VIEW
    // ============================================================

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

//     public function curriculum()
// {
//     $divisionId        = $this->request->getGet('division');
//     $classId        = $this->request->getGet('class_id');
//     $academicYearId = $this->request->getGet('academic_year_id');
//     $termId         = $this->request->getGet('term_id');

//     // ============================================================
//     // STEP 1: BELUM PILIH CLASS
//     // Show Academic Year / Term / Grade / Class selection
//     // ============================================================
//     if (!$classId) {

//         // Semua classes
//         $classes = $this->classModel
//             ->select('classes.*, grades.grade_name, grades.division_id')
//             ->join('grades', 'grades.id = classes.grade')
//             ->where('grades.deleted_at', null)
//             ->where('classes.division_id',$divisionId)
//             ->orderBy('grades.grade_name')
//             ->orderBy('classes.class_name')
//             ->findAll();

//         // Semua academic years
//         $academicYears = $this->academicYearModel
//             ->where('academic_years.division_id',$divisionId)
//             ->orderBy('start_date', 'DESC')
//             ->findAll();

//         // Semua terms + academic year
//         $terms = $this->termModel
//             ->select('
//                 terms.*,
//                 semesters.academic_year_id,
//                 semesters.name as semester_name
//             ')
//             ->join('semesters', 'semesters.id = terms.semester_id')
//             ->orderBy('terms.start_date', 'DESC')
//             ->findAll();

//         return view('gradebook/curriculum_select', [
//             'classes'                => $classes,
//             'academicYears'          => $academicYears,
//             'terms'                  => $terms,
//             'selectedAcademicYearId' => $academicYearId,
//             'selectedTermId'         => $termId,
//         ]);
//     }

//     // ============================================================
//     // STEP 2: CLASS SUDAH DIPILIH
//     // Show all subjects + grades
//     // ============================================================

//     if (!$termId) {
//         throw new \CodeIgniter\Exceptions\PageNotFoundException(
//             'term_id wajib diisi.'
//         );
//     }

//     // Class
//     $class = $this->classModel
//         ->select('classes.*, grades.grade_name, grades.division_id')
//         ->join('grades', 'grades.id = classes.grade')
//         ->where('classes.id', $classId)
//         ->first();

//     if (!$class) {
//         throw new \CodeIgniter\Exceptions\PageNotFoundException(
//             'Class not found.'
//         );
//     }

//     // Term
//     $term = $this->termModel->find($termId);

//     if (!$term) {
//         throw new \CodeIgniter\Exceptions\PageNotFoundException(
//             'Term not found.'
//         );
//     }

//     // Semester
//     $semester = $this->semesterModel->find($term['semester_id']);

//     if (!$semester) {
//         throw new \CodeIgniter\Exceptions\PageNotFoundException(
//             'Semester not found.'
//         );
//     }

//     // Academic year derived from term
//     $academicYearId = $semester['academic_year_id'];

//     $academicYear = $this->academicYearModel->find($academicYearId);

//     // ============================================================
//     // STUDENTS
//     // ============================================================

//     $students = $this->studentModel
//         ->where('class_id', $classId)
//         ->where('deleted_at', null)
//         ->orderBy('name', 'ASC')
//         ->findAll();

//     // ============================================================
//     // SUBJECTS
//     // ============================================================

//     $subjects = $this->subjectModel
//         ->where('division_id', $class['division_id'])
//         ->orderBy('subject_name', 'ASC')
//         ->findAll();

//     // ============================================================
//     // GRADEBOOKS
//     // ============================================================

//     $gradebooks = $this->gradebookModel
//         ->where('class_id', $classId)
//         ->where('term_id', $termId)
//         ->findAll();

//     $gradebookMap = [];

//     foreach ($gradebooks as $gradebook) {
//         $gradebookMap[$gradebook['subject_id']] = $gradebook;
//     }

//     // ============================================================
//     // SCORES
//     // ============================================================

//     $scores = [];

//     if (!empty($gradebooks)) {

//         $gradebookIds = array_column($gradebooks, 'id');

//         $rawScores = $this->gradebookScoreModel
//             ->whereIn('gradebook_id', $gradebookIds)
//             ->findAll();

//         foreach ($rawScores as $score) {

//             $scores[$score['gradebook_id']][$score['student_id']] = $score;
//         }
//     }

//     // ============================================================
//     // SUBJECT + SCORE DATA
//     // ============================================================

//     $subjectGrades = [];

//     foreach ($subjects as $subject) {

//         $gradebook = $gradebookMap[$subject['id']] ?? null;

//         $subjectGrades[] = [
//             'subject'   => $subject,
//             'gradebook' => $gradebook,
//             'scores'    => $gradebook
//                 ? ($scores[$gradebook['id']] ?? [])
//                 : [],
//         ];
//     }
//     exit();
//     return view('gradebook/curriculum', [
//         'class'          => $class,
//         'students'       => $students,
//         'subjects'       => $subjectGrades,
//         'term'           => $term,
//         'semester'       => $semester,
//         'academicYear'   => $academicYear,
//         'classId'        => $classId,
//         'termId'         => $termId,
//         'academicYearId' => $academicYearId,
//     ]);
// }

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

    public function report()
{
    $academicYearId = $this->request->getGet('academic_year_id');
    $termId         = $this->request->getGet('term_id');
    $classId        = $this->request->getGet('class_id');

    // ============================================================
    // Initial page
    // ============================================================

    $academicYears = $this->academicYearModel
        ->orderBy('start_date', 'DESC')
        ->findAll();

    $terms = $this->termModel
        ->select('
            terms.*,
            semesters.academic_year_id,
            semesters.name as semester_name
        ')
        ->join(
            'semesters',
            'semesters.id = terms.semester_id'
        )
        ->orderBy('terms.start_date', 'DESC')
        ->findAll();

    $classes = $this->classModel
        ->select('
            classes.*,
            grades.grade_name
        ')
        ->join(
            'grades',
            'grades.id = classes.grade'
        )
        ->where('grades.deleted_at', null)
        ->orderBy('grades.grade_name')
        ->orderBy('classes.class_name')
        ->findAll();

    // ============================================================
    // If class is selected, get students
    // ============================================================

    $students = [];

    if ($academicYearId && $termId && $classId) {

        // Validate term
        $term = $this->termModel->find($termId);

        if (!$term) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                'Term not found.'
            );
        }

        // Make sure term belongs to selected academic year
        $semester = $this->semesterModel->find($term['semester_id']);

        if (
            !$semester ||
            $semester['academic_year_id'] != $academicYearId
        ) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                'Invalid academic year / term combination.'
            );
        }

        $students = $this->studentModel
            ->where('class_id', $classId)
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    return view('gradebook/report', [
        'academicYears'        => $academicYears,
        'terms'                => $terms,
        'classes'              => $classes,

        'students'             => $students,

        'selectedAcademicYearId' => $academicYearId,
        'selectedTermId'         => $termId,
        'selectedClassId'        => $classId,
    ]);
}
}