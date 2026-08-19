<?php
namespace App\Controllers;

use App\Models\AcademicYearModel;
use App\Models\DivisionModel;
use App\Models\SemesterModel;
use App\Models\TermModel;

class AcademicYearController extends BaseController
{
    protected $academicYearModel;
    protected $divisionModel;
     protected $semesterModel;
    protected $termModel;

    public function __construct()
    {
        $this->academicYearModel = new AcademicYearModel();
        $this->divisionModel     = new DivisionModel();
        $this->semesterModel     = new SemesterModel();
        $this->termModel         = new TermModel();
    }

    public function index()
    {
        $divisionId      = $this->request->getGet('division');
        $divisions = $this->divisionModel
        ->where('id', $divisionId)->findAll();

        $academicYears = $this->academicYearModel
            ->select('academic_years.*, divisions.division_name')
            ->join('divisions', 'divisions.id = academic_years.division_id')
            ->where('division_id', $divisionId)
            ->orderBy('divisions.division_name')
            ->orderBy('academic_years.start_date', 'DESC')
            ->findAll();

        return view('academic_year/index', [
            'academicYears' => $academicYears,
            'divisions'     => $divisions,
        ]);
    }

    public function create()
    {
        $data = [
            'division_id' => $this->request->getPost('division_id'),
            'name'        => $this->request->getPost('name'),
            'start_date'  => $this->request->getPost('start_date'),
            'end_date'    => $this->request->getPost('end_date'),
            'is_active'   => 0,
        ];

        if (!$data['division_id'] || !$data['name'] || !$data['start_date'] || !$data['end_date']) {
            session()->setFlashdata('error', 'Semua field wajib diisi.');
            return redirect()->back()->withInput();
        }

        $this->academicYearModel->insert($data);
        session()->setFlashdata('success', 'Tahun ajaran berhasil dibuat.');
        return redirect()->to(base_url('academic-year'));
    }

    public function update($id)
    {
        $ay = $this->academicYearModel->find($id);
        if (!$ay) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $this->academicYearModel->update($id, [
            'name'       => $this->request->getPost('name'),
            'start_date' => $this->request->getPost('start_date'),
            'end_date'   => $this->request->getPost('end_date'),
            // division_id sengaja tidak bisa diubah setelah dibuat —
            // memindahkan academic year antar divisi berisiko merusak
            // relasi semesters/terms/gradebooks yang sudah ada di bawahnya.
        ]);

        session()->setFlashdata('success', 'Tahun ajaran berhasil diperbarui.');
        return redirect()->to(base_url('academic-year'));
    }

    public function setActive($id)
    {
        $ok = $this->academicYearModel->setActive($id);

        if ($ok) {
            session()->setFlashdata('success', 'Tahun ajaran berhasil diaktifkan.');
        } else {
            session()->setFlashdata('error', 'Gagal mengaktifkan tahun ajaran.');
        }

        return redirect()->to(base_url('academic-year'));
    }

      public function detail($academicYearId)
    {
        $academicYear = $this->academicYearModel->find($academicYearId);
        if (!$academicYear) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $semesters = $this->semesterModel->getByAcademicYear($academicYearId);

        // Ambil semua term untuk semester-semester ini sekaligus, lalu kelompokkan
        // (hindari query per-semester di dalam loop view)
        $semesterIds = array_column($semesters, 'id');
        $terms = [];
        if (!empty($semesterIds)) {
            $allTerms = $this->termModel->whereIn('semester_id', $semesterIds)
                ->orderBy('number', 'ASC')
                ->findAll();
            foreach ($allTerms as $t) {
                $terms[$t['semester_id']][] = $t;
            }
        }

        return view('academic_year/detail', [
            'academicYear' => $academicYear,
            'semesters'    => $semesters,
            'termsBySemester' => $terms,
        ]);
    }

    public function addSemester($academicYearId)
    {
        $academicYear = $this->academicYearModel->find($academicYearId);
        if (!$academicYear) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $name   = $this->request->getPost('name');
        $number = $this->request->getPost('number');

        if (!$name || !$number) {
            session()->setFlashdata('error', 'Nama & nomor semester wajib diisi.');
            return redirect()->to(base_url('academic-year/' . $academicYearId));
        }

        $this->semesterModel->insert([
            'academic_year_id' => $academicYearId,
            'name'             => $name,
            'number'           => $number,
        ]);

        session()->setFlashdata('success', 'Semester berhasil ditambahkan.');
        return redirect()->to(base_url('academic-year/' . $academicYearId));
    }

    public function addTerm($semesterId)
    {
        $semester = $this->semesterModel->find($semesterId);
        if (!$semester) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $name      = $this->request->getPost('name');
        $number    = $this->request->getPost('number');
        $startDate = $this->request->getPost('start_date');
        $endDate   = $this->request->getPost('end_date');

        if (!$name || !$number || !$startDate || !$endDate) {
            session()->setFlashdata('error', 'Semua field term wajib diisi.');
            return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
        }

        if ($startDate > $endDate) {
            session()->setFlashdata('error', 'Tanggal mulai tidak boleh setelah tanggal selesai.');
            return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
        }

        $this->termModel->insert([
            'semester_id' => $semesterId,
            'name'        => $name,
            'number'      => $number,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'is_locked'   => 0,
        ]);

        session()->setFlashdata('success', 'Term berhasil ditambahkan.');
        return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
    }

    public function updateSemester($semesterId)
    {
        $semester = $this->semesterModel->find($semesterId);
        if (!$semester) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $this->semesterModel->update($semesterId, [
            'name'   => $this->request->getPost('name'),
            'number' => $this->request->getPost('number'),
        ]);

        session()->setFlashdata('success', 'Semester berhasil diperbarui.');
        return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
    }

    public function updateTerm($termId)
    {
        $term = $this->termModel->find($termId);
        if (!$term) throw new \CodeIgniter\Exceptions\PageNotFoundException();

        $semester = $this->semesterModel->find($term['semester_id']);

        $startDate = $this->request->getPost('start_date');
        $endDate   = $this->request->getPost('end_date');

        if ($startDate > $endDate) {
            session()->setFlashdata('error', 'Tanggal mulai tidak boleh setelah tanggal selesai.');
            return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
        }

        $this->termModel->update($termId, [
            'name'       => $this->request->getPost('name'),
            'number'     => $this->request->getPost('number'),
            'start_date' => $startDate,
            'end_date'   => $endDate,
        ]);

        session()->setFlashdata('success', 'Term berhasil diperbarui.');
        return redirect()->to(base_url('academic-year/' . $semester['academic_year_id']));
    }
}