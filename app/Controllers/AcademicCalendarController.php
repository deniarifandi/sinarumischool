<?php

namespace App\Controllers;

use App\Models\DivisionModel;
use App\Models\AcademicYearModel;
use App\Models\TermModel;
use App\Models\UserModel;
use App\Models\AcademicCalendarEventModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AcademicCalendarController extends BaseController
{
    private function assertManager(): void
    {
        $userId = session()->get('id') ?? session()->get('user_id');
        $user = $userId ? (new UserModel())->find($userId) : null;
        if (!$user || !in_array($user['role'], ['superadmin', 'teacher_admin'], true)) {
            throw new PageNotFoundException('Anda tidak memiliki akses ke halaman ini.');
        }
    }

    private function eventContext(): array
    {
        $this->assertManager();
        $divisionId = $this->request->getPost('division');
        $yearId = $this->request->getPost('academic_year_id');
        if (!is_string($divisionId) || !ctype_digit($divisionId)
            || !is_string($yearId) || !ctype_digit($yearId)) {
            throw new PageNotFoundException('Periode tidak valid.');
        }
        $year = (new AcademicYearModel())->where('id', $yearId)->where('division_id', $divisionId)->first();
        if (!$year || !(new DivisionModel())->find($divisionId)) {
            throw new PageNotFoundException('Tahun ajaran tidak tersedia untuk divisi ini.');
        }
        return $year;
    }

    private function backUrl(array $year): string
    {
        return base_url('academic-calendar') . '?' . http_build_query([
            'division'=>$year['division_id'], 'academic_year_id'=>$year['id'],
            'month'=>is_string($this->request->getPost('month')) ? $this->request->getPost('month') : '',
        ]);
    }

    public function save($id = null)
    {
        $year = $this->eventContext();
        $model = new AcademicCalendarEventModel();
        if ($id && !$model->where('id', $id)->where('academic_year_id', $year['id'])->first()) {
            throw new PageNotFoundException('Event tidak ditemukan.');
        }
        $data = [];
        foreach (['title','description','start_date','end_date','category','color'] as $field) {
            $value = $this->request->getPost($field);
            $data[$field] = is_string($value) ? trim($value) : '';
        }
        $errors = \App\Libraries\AcademicCalendar::validateEvent($data, $year);
        if ($errors) {
            return redirect()->to($this->backUrl($year))->withInput()
                ->with('calendar_errors', $errors)->with('calendar_edit_id', $id ?? 'new');
        }
        $data['academic_year_id'] = $year['id'];
        $ok = $id ? $model->update($id, $data) : $model->insert($data);
        return redirect()->to($this->backUrl($year))
            ->with($ok ? 'calendar_success' : 'calendar_errors', $ok ? 'Event berhasil disimpan.' : ['Event gagal disimpan.']);
    }

    public function delete($id)
    {
        $year = $this->eventContext();
        $model = new AcademicCalendarEventModel();
        $event = $model->where('id', $id)->where('academic_year_id', $year['id'])->first();
        if (!$event) {
            throw new PageNotFoundException('Event tidak ditemukan.');
        }
        $ok = $model->delete($event['id']);
        return redirect()->to($this->backUrl($year))
            ->with($ok ? 'calendar_success' : 'calendar_errors', $ok ? 'Event berhasil dihapus.' : ['Event gagal dihapus.']);
    }

    public function index()
    {
        $this->assertManager();
        $divisionId = $this->request->getGet('division');
        if (!is_string($divisionId) || !ctype_digit($divisionId)) {
            throw new PageNotFoundException('Divisi tidak valid.');
        }
        $division = (new DivisionModel())->find((int) $divisionId);
        if (!$division) {
            throw new PageNotFoundException('Divisi tidak ditemukan.');
        }
        $academicYears = (new AcademicYearModel())->getByDivision($division['id']);
        $yearId = $this->request->getGet('academic_year_id');
        $selectedYear = null;
        if ($yearId !== null && $yearId !== '') {
            if (!is_string($yearId) || !ctype_digit($yearId)) {
                throw new PageNotFoundException('Tahun ajaran tidak valid.');
            }
            foreach ($academicYears as $year) {
                if ((int) $year['id'] === (int) $yearId) {
                    $selectedYear = $year;
                    break;
                }
            }
            if (!$selectedYear) {
                throw new PageNotFoundException('Tahun ajaran tidak tersedia untuk divisi ini.');
            }
        } else {
            foreach ($academicYears as $year) {
                if (!empty($year['is_active'])) {
                    $selectedYear = $year;
                    break;
                }
            }
            $selectedYear ??= $academicYears[0] ?? null;
        }

        $terms = $selectedYear ? (new TermModel())
            ->select('terms.*, semesters.name as semester_name')
            ->join('semesters', 'semesters.id = terms.semester_id')
            ->where('semesters.academic_year_id', $selectedYear['id'])
            ->orderBy('semesters.number', 'ASC')
            ->orderBy('terms.number', 'ASC')
            ->findAll() : [];

        $events = $selectedYear ? (new AcademicCalendarEventModel())->where('academic_year_id', $selectedYear['id'])
            ->orderBy('start_date', 'ASC')->orderBy('id', 'ASC')->findAll() : [];
        $calendar = null;
        if ($selectedYear) {
            $calendar = (new \App\Libraries\AcademicCalendar())->build(
                $selectedYear, $terms, $this->request->getGet('month'), $events
            );
        }

        return view('academic_calendar/index', [
            'division' => $division,
            'academicYears' => $academicYears,
            'selectedYear' => $selectedYear,
            'terms' => $terms,
            'calendar' => $calendar,
            'events' => $events,
            'categories' => AcademicCalendarEventModel::CATEGORIES,
        ]);
    }
}
