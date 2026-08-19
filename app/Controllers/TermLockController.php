<?php
namespace App\Controllers;

use App\Models\AcademicYearModel;
// use App\Models\SemesterModel;
use App\Models\TermModel;
use App\Models\GradebookModel;

use App\Models\ClassModel;
use App\Models\SubjectModel;

class TermLockController extends BaseController
{
    protected $academicYearModel;
    // protected $semesterModel;
    protected $termModel;
    protected $gradebookModel;
    protected $classModel;
    protected $subjectModel;

    public function __construct()
    {
        $this->academicYearModel = new AcademicYearModel();
        // $this->semesterModel     = new SemesterModel();
        $this->termModel         = new TermModel();
        $this->gradebookModel    = new GradebookModel();
        $this->classModel        = new ClassModel();
        $this->subjectModel      = new SubjectModel();
    }

    // Daftar semua term + status lock
    public function index()
    {

        $divisionId = $this->request->getGet('division');

        $terms = $this->termModel
            ->select('divisions.division_name,terms.*, semesters.name as semester_name, academic_years.name as academic_year_name')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
            ->join('divisions','divisions.id = academic_years.division_id')
            ->where('academic_years.division_id',$divisionId)
            ->orderBy('academic_years.start_date', 'DESC')
            ->orderBy('terms.start_date', 'DESC')
            ->findAll();

        return view('lock/term_list', ['terms' => $terms]);
    }

    public function lockTerm($termId)
    {
        $this->termModel->lockTerm($termId);
        session()->setFlashdata('success', 'Term berhasil dikunci. Semua gradebook di term ini ikut terkunci.');
        return redirect()->back();
    }

    public function unlockTerm($termId)
    {
        $this->termModel->unlockTerm($termId);
        session()->setFlashdata('success', 'Term berhasil dibuka.');
        return redirect()->back();
    }

    // Detail gradebook dalam satu term, untuk override per-gradebook
    public function termDetail($termId)
    {
        $term = $this->termModel->find($termId);
        if (!$term) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $gradebooks = $this->gradebookModel
                // ->select('gradebooks.*')
            ->select('academic_years.name as academic_year_name, gradebooks.*, classes.class_name, subjects.subject_name')
            ->join('terms','gradebooks.term_id = terms.id')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->join('academic_years', 'academic_years.id = semesters.academic_year_id')
            ->join('classes', 'classes.id = gradebooks.class_id')
            ->join('subjects', 'subjects.id = gradebooks.subject_id')
            ->where('gradebooks.term_id', $termId)
            ->orderBy('classes.class_name')
            ->orderBy('subjects.subject_name')
            ->findAll();
        // echo json_encode($gradebooks);
        // exit();
        return view('lock/term_detail', [
            'term'       => $term,
            'gradebooks' => $gradebooks,
        ]);
    }

    public function overrideGradebook($gradebookId)
    {
        $action = $this->request->getPost('action'); // 'lock' | 'unlock' | 'reset'

        if ($action === 'reset') {
            $this->gradebookModel->resetOverride($gradebookId);
            session()->setFlashdata('success', 'Override dibatalkan, gradebook kembali mengikuti status term.');
        } else {
            $this->gradebookModel->overrideLock($gradebookId, $action === 'lock');
            session()->setFlashdata('success', 'Status gradebook berhasil di-override.');
        }

        return redirect()->back();
    }
}