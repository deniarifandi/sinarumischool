<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TeachingJournalModel;
use App\Models\SubjectModel;
use App\Models\ClassModel;
use App\Models\UserSubjectModel;
use App\Models\UserModel;
use App\Models\UnitModel;
use App\Models\SubunitModel;

class TeachingJournalController extends BaseController
{
    protected TeachingJournalModel $journalModel;
    protected SubjectModel         $subjectModel;
    protected ClassModel           $classModel;
    protected UserSubjectModel     $userSubjectModel;
    protected UserModel            $userModel;
    protected UnitModel            $unitModel;
    protected SubunitModel         $subunitModel;

    public function __construct()
    {
        $this->journalModel     = new TeachingJournalModel();
        $this->subjectModel     = new SubjectModel();
        $this->classModel       = new ClassModel();
        $this->userSubjectModel = new UserSubjectModel();
        $this->userModel        = new UserModel();
        $this->unitModel        = new UnitModel();
        $this->subunitModel     = new SubunitModel();
    }

    /**
     * Resolve the current user's role from DB.
     * Returns '' when not logged in.
     */
    private function currentRole(): string
    {
        $uid = (int) (session('id') ?? 0);
        if (! $uid) {
            return '';
        }
        $u = $this->userModel->find($uid);
        return (string) ($u['role'] ?? '');
    }

    /**
     * True if the current user can see / manage every teacher's journals.
     */
    private function isAdmin(): bool
    {
        return in_array($this->currentRole(), ['admin', 'superadmin', 'teacher_admin'], true);
    }

    /**
     * List journals with filters.
     */
    public function index()
    {
        $currentUserId = (int) (session('id') ?? 0);
        $isAdmin       = $this->isAdmin();

        // Filters from query string
        $filters = [
            'teacher_id' => $this->request->getGet('teacher_id'),
            'subject_id' => $this->request->getGet('subject_id'),
            'class_id'   => $this->request->getGet('class_id'),
            'date_from'  => $this->request->getGet('date_from'),
            'date_to'    => $this->request->getGet('date_to'),
        ];

        // Resolve the active subject (required). Without it we redirect to the
        // dashboard — journals are always organised under a subject.
        $subject = null;
        if (! empty($filters['subject_id'])) {
            $subject = $this->subjectModel->find((int) $filters['subject_id']);
        }

        if (! $subject) {
            return redirect()->to('/')
                ->with('error', 'Pilih subject dari Curriculum Subjects Matrix untuk membuka journal.');
        }

        // Default teacher filter = current user (for non-admins)
        if (empty($filters['teacher_id']) && $currentUserId && ! $isAdmin) {
            $filters['teacher_id'] = $currentUserId;
        }

        $journals = $this->journalModel->getList($filters);

        // Dropdown data
        $teachers = $isAdmin
            ? $this->userModel->where('role', 'guru')->orderBy('name', 'ASC')->findAll()
            : [];

        // Classes are filtered by the subject's division.
        $classes = $this->classModel
            ->select('classes.*, grades.grade_name')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.division_id', (int) ($subject['division_id'] ?? 0))
            ->orderBy('classes.class_name', 'ASC')
            ->findAll();

        return view('journal/index', [
            'journals'      => $journals,
            'filters'       => $filters,
            'subject'       => $subject,
            'teachers'      => $teachers,
            'classes'       => $classes,
            'currentUserId' => $currentUserId,
            'isAdmin'       => $isAdmin,
        ]);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $currentUserId   = (int) (session('id') ?? 0);
        $isAdmin         = $this->isAdmin();
        $preselected     = $this->request->getGet('teacher_id') ?? $currentUserId;
        $preselectedSubj = $this->request->getGet('subject_id');

        // Subject is automatic and read-only when reached from the dashboard.
        $subject = null;
        if ($preselectedSubj) {
            $subject = $this->subjectModel->find((int) $preselectedSubj);

            // Security: non-admin teachers can only create a journal for a subject
            // they actually teach (via user_subjects).
            if ($subject && ! $isAdmin) {
                $assigned = $this->userSubjectModel->getAssignedUserIds((int) $subject['id']);
                if (! in_array($currentUserId, $assigned, true)) {
                    return redirect()->to('journal')
                        ->with('error', 'You are not assigned to this subject.');
                }
            }
        }

        // Grades that have classes AND belong to the subject's division (or all if no subject).
        $grades = $this->classModel
            ->select('grades.id, grades.grade_name')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.deleted_at', null)
            ->groupBy('grades.id, grades.grade_name')
            ->orderBy('grades.grade_name', 'ASC');

        if ($subject && isset($subject['division_id'])) {
            $grades = $grades->where('classes.division_id', (int) $subject['division_id']);
        }

        $grades = $grades->findAll();

        return view('journal/form', [
            'mode'        => 'create',
            'journal'     => null,
            'teacherId'   => (int) $preselected,
            'subject'     => $subject,
            'subjectId'   => $subject ? (int) $subject['id'] : null,
            'grades'      => $grades,
            'classes'     => [],
            'units'       => [],
            'subunits'    => [],
            'isAdmin'     => $isAdmin,
        ]);
    }

    /**
     * AJAX: classes filtered by subject's division and grade.
     */
    public function classes()
    {
        $subjectId = (int) $this->request->getGet('subject_id');
        $gradeId   = (int) $this->request->getGet('grade_id');

        $builder = $this->classModel
            ->select('classes.id, classes.class_name, classes.grade, grades.grade_name')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.deleted_at', null);

        if ($subjectId) {
            $subj = $this->subjectModel->find($subjectId);
            if ($subj && isset($subj['division_id'])) {
                $builder = $builder->where('classes.division_id', (int) $subj['division_id']);
            }
        }

        if ($gradeId) {
            $builder = $builder->where('classes.grade', $gradeId);
        }

        $classes = $builder
            ->orderBy('classes.class_name', 'ASC')
            ->findAll();

        return $this->response->setJSON(['classes' => $classes]);
    }

    /**
     * AJAX: units for a given subject + grade.
     */
    public function units()
    {
        $subjectId = (int) $this->request->getGet('subject_id');
        $gradeId   = (int) $this->request->getGet('grade_id');

        if (! $subjectId || ! $gradeId) {
            return $this->response->setJSON(['units' => []]);
        }

        $units = $this->unitModel
            ->select('units.id, units.name')
            ->join('subjects', 'subjects.id = units.subject_id', 'left')
            ->groupStart()
                ->where('units.subject_id', $subjectId)
                ->orWhere('subjects.subject_name', 'All Subject')
            ->groupEnd()
            ->where('units.grade_id', $gradeId)
            ->where('units.deleted_at', null)
            ->orderBy('units.name', 'ASC')
            ->findAll();

        return $this->response->setJSON(['units' => $units]);
    }

    /**
     * AJAX: subunits for a given unit.
     */
    public function subunits()
    {
        $unitId = (int) $this->request->getGet('unit_id');

        if (! $unitId) {
            return $this->response->setJSON(['subunits' => []]);
        }

        $subunits = $this->subunitModel
            ->select('id, subunit_name')
            ->where('unit_id', $unitId)
            ->orderBy('id', 'ASC')
            ->findAll();

        return $this->response->setJSON(['subunits' => $subunits]);
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $journal = $this->journalModel->find($id);

        if (!$journal) {
            return redirect()->to('journal')
                ->with('error', 'Journal not found');
        }

        $isAdmin       = $this->isAdmin();
        $currentUserId = (int) (session('id') ?? 0);

        // Teachers can only edit their own journal
        if (! $isAdmin && (int) $journal['teacher_id'] !== $currentUserId) {
            return redirect()->to('journal')
                ->with('error', 'You are not allowed to edit this journal');
        }

        $subject = $this->subjectModel->find((int) $journal['subject_id']);

        // Find the class + its grade to populate the grade select.
        $classRow = $this->classModel
            ->select('classes.*, grades.grade_name, grades.id AS grade_id')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.id', (int) $journal['class_id'])
            ->first();

        // Grades: those belonging to the subject's division.
        $grades = $this->classModel
            ->select('grades.id, grades.grade_name')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.deleted_at', null)
            ->groupBy('grades.id, grades.grade_name')
            ->orderBy('grades.grade_name', 'ASC');

        if ($subject && isset($subject['division_id'])) {
            $grades = $grades->where('classes.division_id', (int) $subject['division_id']);
        }

        $grades = $grades->findAll();

        $classes = $this->classModel
            ->select('classes.*, grades.grade_name')
            ->join('grades', 'grades.id = classes.grade', 'left');

        if ($subject && isset($subject['division_id'])) {
            $classes = $classes->where('classes.division_id', (int) $subject['division_id']);
        }

        $classes = $classes->orderBy('classes.class_name', 'ASC')->findAll();

        // Pre-populate units for the saved grade, and subunits for the saved unit.
        $units    = [];
        $subunits = [];

        if (! empty($journal['class_id'])) {
            $units = $this->unitModel
                ->select('units.id, units.name')
                ->join('subjects', 'subjects.id = units.subject_id', 'left')
                ->groupStart()
                    ->where('units.subject_id', (int) $journal['subject_id'])
                    ->orWhere('subjects.subject_name', 'All Subject')
                ->groupEnd()
                ->where('units.grade_id', (int) ($classRow['grade'] ?? 0))
                ->where('units.deleted_at', null)
                ->orderBy('units.name', 'ASC')
                ->findAll();

            if (! empty($journal['unit_id'])) {
                $subunits = $this->subunitModel
                    ->select('id, subunit_name')
                    ->where('unit_id', (int) $journal['unit_id'])
                    ->orderBy('id', 'ASC')
                    ->findAll();
            }
        }

        return view('journal/form', [
            'mode'        => 'edit',
            'journal'     => $journal,
            'teacherId'   => (int) $journal['teacher_id'],
            'subject'     => $subject,
            'subjectId'   => (int) $journal['subject_id'],
            'selectedGrade' => $classRow['grade'] ?? null,
            'grades'      => $grades,
            'classes'     => $classes,
            'units'       => $units,
            'subunits'    => $subunits,
            'isAdmin'     => $isAdmin,
        ]);
    }

    /**
     * Show journal detail.
     */
    public function show($id)
    {
        $journal = $this->journalModel->getDetail((int) $id);

        if (!$journal) {
            return redirect()->to('journal')
                ->with('error', 'Journal not found');
        }

        $isAdmin       = $this->isAdmin();
        $currentUserId = (int) (session('id') ?? 0);

        if (! $isAdmin && (int) $journal['teacher_id'] !== $currentUserId) {
            return redirect()->to('journal')
                ->with('error', 'You are not allowed to view this journal');
        }

        return view('journal/show', [
            'journal' => $journal,
        ]);
    }

    /**
     * Persist a new journal.
     */
    public function store()
    {
        $rules = [
            'date'       => 'required|valid_date',
            'teacher_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'class_id'   => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->collectPostData();

        $this->journalModel->insert($data);

        return redirect()->to('journal')
            ->with('success', 'Teaching journal saved');
    }

    /**
     * Update an existing journal.
     */
    public function update($id)
    {
        $journal = $this->journalModel->find($id);

        if (!$journal) {
            return redirect()->to('journal')->with('error', 'Journal not found');
        }

        $isAdmin       = $this->isAdmin();
        $currentUserId = (int) (session('id') ?? 0);

        if (! $isAdmin && (int) $journal['teacher_id'] !== $currentUserId) {
            return redirect()->to('journal')->with('error', 'Not allowed');
        }

        $rules = [
            'date'       => 'required|valid_date',
            'teacher_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'class_id'   => 'required|integer',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = $this->collectPostData();

        $this->journalModel->update($id, $data);

        return redirect()->to('journal')
            ->with('success', 'Teaching journal updated');
    }

    /**
     * Soft-delete a journal.
     */
    public function delete($id)
    {
        $journal = $this->journalModel->find($id);

        if (!$journal) {
            return redirect()->to('journal')->with('error', 'Journal not found');
        }

        $isAdmin       = $this->isAdmin();
        $currentUserId = (int) (session('id') ?? 0);

        if (! $isAdmin && (int) $journal['teacher_id'] !== $currentUserId) {
            return redirect()->to('journal')->with('error', 'Not allowed');
        }

        $this->journalModel->delete($id);

        return redirect()->to('journal')
            ->with('success', 'Teaching journal deleted');
    }

    /**
     * Print-friendly detail view.
     */
    public function print($id)
    {
        $journal = $this->journalModel->getDetail((int) $id);

        if (!$journal) {
            return redirect()->to('journal')
                ->with('error', 'Journal not found');
        }

        $isAdmin       = $this->isAdmin();
        $currentUserId = (int) (session('id') ?? 0);

        if (! $isAdmin && (int) $journal['teacher_id'] !== $currentUserId) {
            return redirect()->to('journal')
                ->with('error', 'You are not allowed to view this journal');
        }

        return view('journal/print', [
            'journal' => $journal,
        ]);
    }

    /**
     * Class-teacher view: read-only listing of every journal
     * that has been created for a given class (across subjects and teachers).
     *
     * Same gate as the dashboard "Class Room Management" card:
     * superadmin / teacher / teacher_admin. The class teacher is NOT required
     * to be the creator of the journals — this is a viewer.
     */
    public function classList($classId)
    {
        $classId = (int) $classId;
        if (! $classId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class id required.');
        }

        // Same role gate as dashboard's Class Room Management card.
        $role = $this->currentRole();
        if (! in_array($role, ['superadmin', 'teacher', 'teacher_admin'], true)) {
            return redirect()->to('/')
                ->with('error', 'You are not allowed to view class journals.');
        }

        // Class + grade (joined for display).
        $class = $this->classModel
            ->select('classes.*, grades.grade_name, grades.division_id')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.id', $classId)
            ->first();

        if (! $class) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
        }

        // Optional filters from query string: subject_id, teacher_id, date range.
        $filters = [
            'class_id'  => $classId,
            'subject_id' => $this->request->getGet('subject_id'),
            'teacher_id' => $this->request->getGet('teacher_id'),
            'date_from'  => $this->request->getGet('date_from'),
            'date_to'    => $this->request->getGet('date_to'),
        ];

        $journals = $this->journalModel->getList($filters);

        // Dropdowns for filter bar: subjects in this division + teachers.
        $subjects = $this->subjectModel
            ->where('division_id', (int) ($class['division_id'] ?? 0))
            ->where('deleted_at', null)
            ->orderBy('subject_name', 'ASC')
            ->findAll();

        $teachers = $this->userModel
            ->whereIn('role', ['guru', 'teacher', 'teacher_admin'])
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('journal/class_list', [
            'class'    => $class,
            'journals' => $journals,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'filters'  => $filters,
        ]);
    }

    /**
     * Class-teacher view: combined print recap for a given class over a date range.
     *
     * Query string:
     *   - date_from  (Y-m-d, optional; falls back to today)
     *   - date_to    (Y-m-d, optional; falls back to date_from / today)
     *   - subject_id (optional, narrows the recap to one subject)
     *   - teacher_id (optional, narrows the recap to one teacher)
     *
     * Renders ALL matching journals in one printable page, each using the
     * same layout as journal/print.php so the recap prints cleanly.
     */
    public function classPrint($classId)
    {
        $classId = (int) $classId;
        if (! $classId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class id required.');
        }

        $role = $this->currentRole();
        if (! in_array($role, ['superadmin', 'teacher', 'teacher_admin'], true)) {
            return redirect()->to('/')
                ->with('error', 'You are not allowed to view class journals.');
        }

        $class = $this->classModel
            ->select('classes.*, grades.grade_name, grades.division_id')
            ->join('grades', 'grades.id = classes.grade', 'left')
            ->where('classes.id', $classId)
            ->first();

        if (! $class) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Class not found.');
        }

        $dateFrom = $this->request->getGet('date_from');
        $dateTo   = $this->request->getGet('date_to');

        // Only apply the date window when BOTH bounds are provided.
        // If a range is missing, we do NOT restrict by date — otherwise a
        // "filtered" print with no dates would silently fall back to today
        // and show nothing.
        if (!empty($dateFrom) && !empty($dateTo)) {
            // Guard: keep range monotonic.
            if (strtotime($dateTo) < strtotime($dateFrom)) {
                $dateTo = $dateFrom;
            }
        } elseif (empty($dateFrom) && !empty($dateTo)) {
            $dateFrom = $dateTo;
        } elseif (empty($dateTo) && !empty($dateFrom)) {
            $dateTo = $dateFrom;
        }

        $filters = [
            'class_id'   => $classId,
            'subject_id' => $this->request->getGet('subject_id') ?: null,
            'teacher_id' => $this->request->getGet('teacher_id') ?: null,
            'date_from'  => $dateFrom ?: null,
            'date_to'    => $dateTo   ?: null,
        ];

        $journals = $this->journalModel->getList($filters);

        // Recap aggregates.
        $totalJp       = 0;
        $subjectNames  = [];
        $teacherNames  = [];
        foreach ($journals as $j) {
            if (isset($j['periods']) && is_numeric($j['periods'])) {
                $totalJp += (int) $j['periods'];
            }
            if (!empty($j['subject_name']) && !in_array($j['subject_name'], $subjectNames, true)) {
                $subjectNames[] = $j['subject_name'];
            }
            if (!empty($j['teacher_name']) && !in_array($j['teacher_name'], $teacherNames, true)) {
                $teacherNames[] = $j['teacher_name'];
            }
        }

        return view('journal/class_print', [
            'class'        => $class,
            'journals'     => $journals,
            'filters'      => $filters,
            'dateFrom'     => $dateFrom,
            'dateTo'       => $dateTo,
            'totalJp'      => $totalJp,
            'subjectNames' => $subjectNames,
            'teacherNames' => $teacherNames,
        ]);
    }

    /**
     * Build the post data array from request, normalising empty values.
     */
    private function collectPostData(): array
    {
        $post = $this->request->getPost();

        $periods   = $post['periods'] ?? null;
        $unitId    = $post['unit_id']    ?? null;
        $subunitId = $post['subunit_id'] ?? null;

        return [
            'date'        => $post['date'],
            'teacher_id'  => (int) $post['teacher_id'],
            'subject_id'  => (int) $post['subject_id'],
            'class_id'    => (int) $post['class_id'],
            'unit_id'     => $unitId !== null && $unitId !== '' ? (int) $unitId : null,
            'subunit_id'  => $subunitId !== null && $subunitId !== '' ? (int) $subunitId : null,
            'periods'     => $periods !== null && $periods !== '' ? (int) $periods : null,
            'activities'  => $post['activities'] ?? null,
            'notes'       => $post['notes']      ?? null,
        ];
    }
}
