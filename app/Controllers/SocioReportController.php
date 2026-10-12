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
            ;

        if ($role === 'teacher') {
            $builder->where('socioreport.teacher_id', $userId);
        }

        $periods = $builder->findAll();

        // Filter hanya untuk data yang ditampilkan, tidak mengubah data laporan.
        $filterClass = (int) ($this->request->getGet('filter_class') ?? 0);
        $filterMonth = (int) ($this->request->getGet('filter_month') ?? 0);
        $filterYear  = (int) ($this->request->getGet('filter_year') ?? 0);

        $periods = array_values(array_filter($periods, static function ($period) use ($filterClass, $filterMonth, $filterYear) {
            $periodDate = strtotime($period['period']);
            return (!$filterClass || (int) $period['class_id'] === $filterClass)
                && (!$filterMonth || (int) date('n', $periodDate) === $filterMonth)
                && (!$filterYear || (int) date('Y', $periodDate) === $filterYear);
        }));

        return view('socioreport/index', [
            'classes' => $classes,
            'periods' => $periods,
            'filterClass' => $filterClass,
            'filterMonth' => $filterMonth,
            'filterYear' => $filterYear,
        ]);
    }

    /* ======================
       IMPORT CSV / XLSX
       ====================== */
    public function import()
{
    $classId   = (int)$this->request->getPost('class_id');
    $month     = (int)$this->request->getPost('month');
    $year      = (int)$this->request->getPost('year');
    
    $teacherId = session()->get('id');

    $classDetail = $this->classModel->getClassDetail($classId);
    $gradeName   = $classDetail[0]['grade_name'] ?? '';

    $file = $this->request->getFile('csv_file');
    if (!$file || !$file->isValid()) {
        return redirect()->back();
    }

    $periodDate = sprintf('%04d-%02d-01', $year, $month);
    $periodKey  = sprintf('%04d-%02d', $year, $month);

    // Read CSV or XLSX using the same normalized row structure.
    $extension = strtolower($file->getClientExtension());
    try {
        $rows = $this->readImportRows($file->getTempName(), $extension);
    } catch (\Throwable $e) {
        return redirect()->back()
            ->with('error', 'File harus berupa CSV atau XLSX yang valid.');
    }

    if (count($rows) < 2) {
        return redirect()->back()
            ->with('error', 'File tidak berisi data yang cukup untuk diimpor.');
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
        'speaking_english'=>null,'prepared'=>null,'organized'=>null,
        'needs_english_practice'=>null,'unprepared'=>null,'needs_organization'=>null,
    ];

    // ClassDojo exports use descriptive indicator names. Map them to the
    // internal fields used by the print formula for each grade level.
    if (in_array($gradeName, ['Primary 1', 'Primary 2'])) {
        $aliases = [
            'trying'               => 'trying',
            'following directions' => 'following',
            'ready to learn'       => 'calm',
            'self-control'         => 'composed',
            'kind & respectful'    => 'sharing',
            'cooperating'          => 'kindness',
            'giving up'            => 'giving',
            'needs reminders'      => 'reminders',
            'disrupting learning'  => 'upset',
            'unsafe behavior'      => 'reactive',
            'unkind behavior'      => 'not-sharing',
            'not cooperating'      => 'rough',
        ];
    } elseif (in_array($gradeName, ['Primary 3', 'Primary 4'])) {
        $aliases = [
            'focused'              => 'focused',
            'persevering'          => 'attempting',
            'presevering'          => 'attempting',
            'responsible'          => 'accepting',
            'accepting feedback'  => 'recovering',
            'cooperating'         => 'cooperative',
            'respectful listener'  => 'listening',
            'distracted'           => 'distracted',
            'needs encouragement' => 'avoiding',
            'disrupting learning' => 'rejecting',
            'overreacting'         => 'quitting',
            'interrupting others'  => 'disruptive',
            'not cooperating'      => 'interrupting',
        ];
    } else {
        $aliases = [
            'self-motivated'       => 'improving',
            'growth mindset'      => 'challenging',
            'responsible'         => 'steady',
            'respectful'          => 'resilient',
            'leadership'          => 'contributing',
            'supportive'          => 'supporting',
            'avoiding challenges' => 'resisting',
            'needs encouragement' => 'avoidance',
            'disrupting learning' => 'overreacting',
            'poor self-control'   => 'lingering',
            'not contributing'    => 'withdrawing',
            'disrespectful'       => 'dominating',
        ];
    }

    $learningHabitAliases = [
        'speaking english'       => 'speaking_english',
        'prepared'               => 'prepared',
        'organized'              => 'organized',
        'well-organized'         => 'organized',
        'needs english practice' => 'needs_english_practice',
        'unprepared'             => 'unprepared',
        'needs organization'     => 'needs_organization',
    ];
    $aliases = $learningHabitAliases + $aliases;

    foreach ($rows[0] as $x => $cell) {
        $val = strtolower(trim($cell));

        foreach ($aliases as $alias => $key) {
            if (array_key_exists($key, $columns) && $columns[$key] === null && strpos($val, $alias) !== false) {
                $columns[$key] = $x;
            }
        }

        foreach ($columns as $key => $idx) {
            if ($idx === null && strpos($val, $key) !== false) {
                $columns[$key] = $x;
            }
        }
    }

    // Some exports omit indicators that were not used. Keep those fields as zero.
    foreach (array_keys($columns) as $key) {
        if (!array_key_exists($key, $columns)) {
            $columns[$key] = null;
        }
    }

    // hard stop if student column missing
    if ($columns['student'] === null) {
        return redirect()->back()
            ->with('error', 'CSV/XLSX must contain student column');
    }

    // Overwrite existing data only after the uploaded file has been validated.
    $this->reportModel
        ->where('class_id', $classId)
        ->where("DATE_FORMAT(date,'%Y-%m')", $periodKey)
        ->delete();

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

    private function readImportRows(string $path, string $extension): array
    {
        if ($extension === 'csv') {
            $csv = trim((string) file_get_contents($path));
            return array_map('str_getcsv', preg_split("/\r\n|\n|\r/", $csv));
        }

        if ($extension !== 'xlsx' || !class_exists('ZipArchive')) {
            throw new \RuntimeException('Unsupported import format.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Unable to open XLSX file.');
        }

        $xml = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($xml === false) {
            $zip->close();
            throw new \RuntimeException('Worksheet not found.');
        }

        $sharedStrings = [];
        $sharedXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXml !== false) {
            $shared = simplexml_load_string($sharedXml);
            $sharedMain = $shared->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            foreach ($sharedMain->si as $item) {
                $itemMain = $item->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $text = (string) ($itemMain->t ?? '');
                if ($text === '') {
                    foreach ($itemMain->r as $run) {
                        $runMain = $run->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                        $text .= (string) ($runMain->t ?? '');
                    }
                }
                $sharedStrings[] = $text;
            }
        }

        $sheet = simplexml_load_string($xml);
        $rows = [];
        $sheetMain = $sheet->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main');
        foreach ($sheetMain->sheetData->row as $rowNode) {
            $row = [];
            foreach ($rowNode->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main')->c as $cell) {
                $cellMain = $cell->children('http://schemas.openxmlformats.org/spreadsheetml/2006/main');
                $attributes = $cell->attributes();
                $reference = (string) ($attributes['r'] ?? '');
                $columnLetters = preg_replace('/\d+/', '', $reference);
                $columnIndex = 0;
                for ($i = 0; $i < strlen($columnLetters); $i++) {
                    $columnIndex = ($columnIndex * 26) + (ord(strtoupper($columnLetters[$i])) - 64);
                }
                $columnIndex--;

                $value = (string) ($cellMain->v ?? '');
                $cellType = (string) ($attributes['t'] ?? '');
                if ($cellType === 's') {
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($cellType === 'inlineStr') {
                    $value = (string) ($cellMain->is->t ?? '');
                }

                $row[$columnIndex] = $value;
            }

            if ($row !== []) {
                $maxColumn = max(array_keys($row));
                $normalizedRow = array_fill(0, $maxColumn + 1, '');
                foreach ($row as $columnIndex => $value) {
                    $normalizedRow[$columnIndex] = $value;
                }
                $rows[] = $normalizedRow;
            }
        }

        $zip->close();
        return $rows;
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
            $builder->where('socioreport.teacher_id', session()->get('id'));
        }
        // echo $classId;
        // exit();
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
            $builder->where('teacher_id', session()->get('id'));
        }

        $builder->delete();

        return redirect()->to('/socioreport?divisi=3');
    }
}
