<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\PresensidataModel;
use App\Libraries\datatable;
use Config\Database;
use \DateTime;

class Presensidata extends MyResourceController
{

    public $table = "Presensidata";
    public $title = "Attendance Data";
    public $primaryKey = "presensidata_id";

    public $fieldList = [
        ['guru_nama', 'Teacher`s Name'],
        ['created_at', 'Date'],
    // ['divisi_nama','Division']
    // ['presensistatus_nama','Status']
    ];

    public $selectList= [
        'Presensidata.presensidata_id',
        'Presensidata.guru_id',
        'Presensidata.created_at',
        // 'Divisi.divisi_nama',
        'Guru.guru_nama'
    ];

    public $toSearch = 
    [
        'Presensidata.guru_id',
        // 'Divisi.divisi_nama',
        'Guru.guru_nama'
    ];


    public $where = [
    ];


    public $joinTable = [
        ['Guru','Guru.guru_id = Presensidata.guru_id','left'],
        ['Gurudivisi','Guru.guru_id = Gurudivisi.guru_id','left'],
        // ['Divisi','Divisi.divisi_id = Gurudivisi.divisi_id','left']
        // ['Presensistatus','Presensidata.status = Presensistatus.presensistatus_id','left']
    ];

    public $field = [
        ['select','guru_id'],
        ['select','status']
    ];


    public $fieldName = [
        'Name',
        'Status'
    ];

    public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        ['noOption'],

    ];

    public $dataToShow = [];

    public $db;

    public function __construct()
    {   

        $this->db = \Config\Database::connect(); 
        $this->model = new PresensidataModel();
        
        $this->fieldOption[0] = $this->getdata('Guru'); 
        $this->fieldOption[1] = $this->getdata('Presensistatus'); 
        if (session()->get('guru_id') != 0) {
            $this->where = ['Guru.guru_id' => session()->get('guru_id')];
        }
        // 

        $this->dataToShow = $this->prepareDataToShow();
    }

    public function index(){

        return view('presence/index');
    }

    public function data(){

        $builder = Database::connect()->table($this->table);
$builder->select("
    Presensidata.*, 
    Guru.guru_id as Guruguru_id, 
    Guru.guru_nama, 
    (
        SELECT GROUP_CONCAT(DISTINCT Divisi.divisi_nama SEPARATOR ', ')
        FROM Gurudivisi
        JOIN Divisi ON Divisi.divisi_id = Gurudivisi.divisi_id
        WHERE Gurudivisi.guru_id = Guru.guru_id
    ) AS semua_divisi,
    (
        SELECT GROUP_CONCAT(DISTINCT Jabatan.jabatan_nama SEPARATOR ', ')
        FROM Gurujabatan
        JOIN Jabatan ON Jabatan.jabatan_id = Gurujabatan.jabatan_id
        WHERE Gurujabatan.guru_id = Guru.guru_id
    ) AS semua_jabatan,
    Presensidata.longitude,
    Presensidata.latitude,
    DATE_FORMAT(Presensidata.created_at, '%d-%M-%Y') as date_formatted,
    DATE_FORMAT(Presensidata.created_at, '%H:%i') as time_formatted
");
$builder->join('Guru','Presensidata.guru_id = Guru.guru_id');
// remove Gurudivisi, Divisi, Gurujabatan, Jabatan joins here — handled in subqueries

// Optional filter
if (session()->get('guru_id') != 0) {
    $builder->where('Guru.guru_id', session()->get('guru_id'));
}

$datatable = new Datatable();
return $datatable->generate($builder, 'Presensidata.'.$this->primaryKey, $this->toSearch);

    }

    

    public function showForm(){
        return view('/presence/form');
    }

    public function showStatus(){
        return view('/presence/status');
    }

    public function front(){

        $today = new DateTime();

        // Get 21st of previous month
        $startDate = (clone $today)->modify('first day of last month')->setDate(
            $today->format('Y'),
            (clone $today)->modify('first day of last month')->format('m'),
            21
        );

        // Get 20th of *this* month
        $endDate = (clone $today)->setDate(
            $today->format('Y'),
            $today->format('m'),
            20
        );

        // Format for input date
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        

        $this->db = \Config\Database::connect(); 
        $builder = $this->db->table('Divisi');
        $builder->select('Divisi.divisi_id, Divisi.divisi_nama');
        $result = $builder->get()->getResult();

        // print_r($result);
        return view('/presence/front',['divisis' => $result,'start' => $startDateStr, 'end'=> $endDateStr]);
    }

    public function report(){
       // Step 1: Define date range



        $startDate = $_GET['start'];
        $endDate = $_GET['end']; 
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);

        // Get full month names
        $startMonthName = $startDateObj->format('F'); // e.g., "June"
        $endMonthName = $endDateObj->format('F');     // e.g., "July"

// Step 2: Generate dynamic columns for each date
        $dates = [];
        $columns = [];

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        foreach ($period as $date) {
    $label = $date->format('M d'); // Column label e.g. 1 Jul, 2 Jul
    $dateString = $date->format('Y-m-d');

    $dates[] = $label;

    // Replace time with status
    $columns[] = "
    MAX(
    CASE 
    WHEN DATE(p.created_at) = '$dateString' THEN p.status 
    ELSE NULL 
    END
) AS `$label`";
}

// Step 3: Build SQL query
$db = Database::connect();

$divisi_id = $_GET['division'];

$sql = "
SELECT 
g.guru_nama,
j.jabatan_nama,
d.divisi_nama,
" . implode(",\n", $columns) . "
FROM Guru g
JOIN Gurudivisi gd ON gd.guru_id = g.guru_id
JOIN Divisi d ON d.divisi_id = gd.divisi_id
LEFT JOIN Gurujabatan gj ON gj.guru_id = g.guru_id
LEFT JOIN Jabatan j ON j.jabatan_id = gj.jabatan_id
LEFT JOIN Presensidata p 
ON p.guru_id = g.guru_id 
AND DATE(p.created_at) BETWEEN '$startDate' AND '$endDate'
WHERE d.divisi_id = '$divisi_id'
GROUP BY g.guru_id
ORDER BY g.guru_nama
";

$query = $db->query($sql);
$results = $query->getResult();

if (count($results)  < 1) {
    Return view('presence/report');
}

echo "<h2 style='text-align: center;'>Monthly Attendance Report</h2>";
echo "<h3 style='text-align: center;'>".$startMonthName." - ".$endMonthName."</h3>";
echo "<h4 style='margin-bottom:3px; text-align:right'>Division : ".$results[0]->divisi_nama."</h4><br>";
echo "<table style='font-size:10px; width:100%; border-collapse: collapse; border: 1px solid black;' cellpadding='5'>
<thead><tr>
<th rowspan='2' style='border: 1px solid black;'>Nama</th>
<th rowspan='2' style='border: 1px solid black;'>Jabatan</th>
<th rowspan='2' style='border: 1px solid black;'>Divisi</th>        


";

foreach ($dates as $d) {
    echo "<th style='border: 1px solid black;'>$d</th>";
}
echo "<th style='border: 1px solid black;'>Count Day</th>";
echo "<th style='border: 1px solid black;'>Total</th>";

echo "</tr></thead><tbody>";
foreach ($results as $row) {
    $total = 0;
    $countDay = 0;
    echo "<tr>
    <td style='border: 1px solid black;'>{$row->guru_nama}</td>
    <td style='border: 1px solid black;'>{$row->jabatan_nama}</td>
    <td style='border: 1px solid black;'>{$row->divisi_nama}</td>";

    foreach ($dates as $d) {
            echo "<td style='border: 1px solid black; text-align:center'>" . ($row->$d ?? '0') . "</td>"; // use "-" for missing status
            if ($row->$d == 1) {
                $countDay++;
                $total = $total+15000;
            }
        }
        echo "<th style='border: 1px solid black;'>{$countDay}</th>";
        echo "<th style='border: 1px solid black;'>{$total}</th>";


        echo "</tr>";
    }
    echo "</tbody></table>";
}

public function getGuruId(){
    $this->db = \Config\Database::connect(); 

    $nama     = trim($_POST['nama']);
    $status   = $_POST['status'];
    $today = date('Y-m-d');
    $builder = $this->db->table('Guru');
    $builder->select('Guru.*');
    $builder->where('guru_nama', $nama);
    $query = $builder->get();
    $results = $query->getResult();
    if (count($results) > 0) {
        return $results[0]->guru_id;
    }else{
        $result = "Staff Data not Found, contact Administrator";
        $code = 0;
        $title = "Failed";
        echo view('/presence/result.php',[
            'result' => $result,
            'code' => $code,
            'title' => $title
        ]);
    }
}

public function cekPresensi(){
    $builder = $this->db->table('Presensidata');
    $builder->select('Presensidata.*');
    $builder->where('guru_id', $this->getGuruId());
    $builder->where('Presensidata_tanggal', date("Y-m-d"));

    $query = $builder->get();
    $resultsPersonel = $query->getResult();

    return count($resultsPersonel);
}

public function savePresensi(){

    if ($this->cekPresensi() > 0) {
        $result = "It looks like you’ve already submitted your data for today. No need to submit again, everything has been recorded successfully. Thank you for staying consistent!";
        $code = 0;
        $title = "Failed";

    }else{

        $data = [
            'guru_id'          => $this->getGuruId(),
            'longitude'        => $_POST['longitude'],
            'latitude'         => $_POST['latitude'],
            'status'           => $_POST['status']
        ];

        $builder = $this->db->table('Presensidata');
        $builder->insert($data);

        $title = "Success";
        $result = "Your attendance has been successfully recorded for today. Thank you for checking in on time, we appreciate your punctuality and dedication.";
        $code = 1;


            // echo ;
    }

    echo view('/presence/result.php',[
        'result' => $result,
        'code' => $code,
        'title' => $title
    ]);
}

}

