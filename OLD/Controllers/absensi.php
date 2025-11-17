<?php

namespace App\Controllers;

use Config\Database;
use \DateTime;

class absensi extends BaseController
{
    
    public function __construct()
    {
      
    
    }

    public function index(){
        $user =  session()->get('guru_id');
        $builder = Database::connect()->table('Murid');
        $builder->select('absensi.*, Murid.*, Kelompok.*');
        $builder->join('absensi','Murid.murid_id = absensi.murid_id');
        $builder->join('Kelompok','Kelompok.kelompok_id = Murid.kelompok_id','left');
        $builder->join('Guru','Guru.guru_id = Kelompok.guru_id','left');
        $builder->where('Guru.guru_id',$user);
        
        $builder->groupBy('tanggal');
        $builder->orderBy('tanggal','desc');


        // print_r($builder->get()->getResult());
        $data = $builder->get()->getResult();
        return view('mli/listAbsensi',['data' => $data]);
    }

    public function addAbsensi(){
        $user =  session()->get('guru_id');
        // echo $user;
        $builder = Database::connect()->table('Murid');
        $builder->select('Murid.*, Kelompok.*');
        $builder->join('Kelompok','Kelompok.kelompok_id = Murid.kelompok_id','left');
        $builder->join('Guru','Guru.guru_id = Kelompok.guru_id','left');
        $builder->where('Guru.guru_id',$user);
        // print_r($builder->get()->getResult());
        $data = $builder->get()->getResult();
        return view('mli/addAbsensi',['data' => $data]);
    }

    public function editAbsensi($date)
    {
        $user =  session()->get('guru_id');

        $db = \Config\Database::connect();
        $builder = $db->table('absensi');
        
         $builder->select('absensi.*, Murid.*, Kelompok.*');
        $builder->join('Murid', 'Murid.murid_id = absensi.murid_id');
        $builder->join('Kelompok', 'Kelompok.kelompok_id = Murid.kelompok_id');
        $builder->join('Guru','Guru.guru_id = Kelompok.guru_id','left');
        $builder->where('tanggal', $date);
        $builder->where('Guru.guru_id',$user);

        $data['absensi'] = $builder->get()->getResult();
        $data['tanggal'] = $date;
        // print_r($data);
        // echo json_encode($data);
        return view('mli/editAbsensi', ['data' => $data]);
    }

    public function delete($tanggal)
    {
          $user = session()->get('guru_id');

            $db = \Config\Database::connect();

            // Step 1: Get murid_id under this teacher
            $muridBuilder = $db->table('Murid');
            $muridBuilder->select('Murid.murid_id');
            $muridBuilder->join('Kelompok', 'Kelompok.kelompok_id = Murid.kelompok_id');
            $muridBuilder->where('Kelompok.guru_id', $user);
            $muridIDs = $muridBuilder->get()->getResultArray();

            $muridIDList = array_column($muridIDs, 'murid_id');

            if (!empty($muridIDList)) {
                // Step 2: Delete from absensi where date and murid_id match
                $absenBuilder = $db->table('absensi');
                $absenBuilder->where('tanggal', $tanggal);
                $absenBuilder->whereIn('murid_id', $muridIDList);
                $absenBuilder->delete();

                session()->setFlashdata('success', 'Student attendance on ' . date('j-M-Y', strtotime($tanggal)) . ' deleted.');
            } else {
                session()->setFlashdata('error', 'No students found for this teacher.');
            }

            return redirect()->to(site_url('absensi'));
    }

  public function saveAbsensi()
{

    // echo $this->request->getPost('date');;
    $db = \Config\Database::connect();
    $builder = $db->table('absensi'); // your attendance table name

    $murid_ids = $this->request->getPost('murid_id');      // array of student IDs
    $attendances = $this->request->getPost('attendance');  // array of attendance statuses
    $keterangans = $this->request->getPost('keterangan');
    $date = $this->request->getPost('date'); // or get from form input if needed

    for ($i = 0; $i < count($murid_ids); $i++) {
        // Prepare data for each student
        $data = [
            'murid_id' => $murid_ids[$i],
            'status'   => $attendances[$i], // 1 = present, 2 = absent, 3 = sick
            'tanggal'  => $date,
            'absensi_keterangan' => $keterangans[$i]
        ];

        // Insert or update logic (optional: check if record exists for that date + murid)
        $existing = $builder
            ->where('murid_id', $murid_ids[$i])
            ->where('tanggal', $date)
            ->get()
            ->getRow();

        if ($existing) {
            // Update existing record
            $builder->where('absensi_id', $existing->absensi_id)->update($data);
        } else {
            // Insert new record
            $builder->insert($data);
        }
    }

     session()->setFlashdata('success', 'Student Attendance Filled');
    return redirect()->to(site_url('absensi'));
}

 public function front(){

        $today = new DateTime();

        // Get 21st of previous month
        $startDate = (clone $today)->setDate(
            $today->format('Y'),
            $today->format('m'),
            1
        );

        // Get 20th of *this* month
       $endDate = (clone $today)->modify('last day of this month');

        // Format for input date
        $startDateStr = $startDate->format('Y-m-d');
        $endDateStr = $endDate->format('Y-m-d');

        

        $this->db = \Config\Database::connect(); 
        $builder = $this->db->table('Kelompok');
        $builder->select('Kelompok.kelompok_id, Kelompok.kelompok_nama');
        $builder->where('deleted_at',null);
        $builder->orderBy('kelompok_nama');
        $result = $builder->get()->getResult();

        // print_r($result);
        return view('/presence/front_murid',['kelompoks' => $result,'start' => $startDateStr, 'end'=> $endDateStr]);
    }

    public function result(){
        // Get inputs
    $startDate = $this->request->getGet('start');
    $endDate = $this->request->getGet('end');
    $kelompok_id = $this->request->getGet('kelompok');

    // Format dates
    $startDateObj = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);
    $startMonthName = $startDateObj->format('F');
    $endMonthName = $endDateObj->format('F');

    // Generate dynamic columns
    $dates = [];
    $columns = [];
    $period = new \DatePeriod(
        new \DateTime($startDate),
        new \DateInterval('P1D'),
        (new \DateTime($endDate))->modify('+1 day')
    );

    foreach ($period as $date) {
        $label = $date->format('M d');
        $dateString = $date->format('Y-m-d');
        $dates[] = $label;
        $columns[] = "
            MAX(
                CASE 
                    WHEN DATE(a.tanggal) = '$dateString' THEN a.status 
                    ELSE NULL 
                END
            ) AS `$label`";
    }

    // Build query
    $db = \Config\Database::connect();
    $sql = "
    SELECT 
        m.murid_nama,
        k.kelompok_nama,
        a.tanggal,
        " . implode(",\n", $columns) . "
    FROM Murid m
    LEFT JOIN Kelompok k ON k.kelompok_id = m.kelompok_id
    LEFT JOIN absensi a ON m.murid_id = a.murid_id 
        -- AND DATE(a.created_at) BETWEEN '$startDate' AND '$endDate'
    WHERE k.kelompok_id = '$kelompok_id'
    GROUP BY m.murid_id
    ORDER BY m.murid_id
    ";

    $query = $db->query($sql);
    $results = $query->getResult();
    // print_r($results);

    // if (count($results) < 1) {
    //     //return view('presence/report'); // fallback
    // }

    return view('presence/reportmurid', [
        'results' => $results,
        'dates' => $dates,
        'startMonth' => $startMonthName,
        'endMonth' => $endMonthName,
        'kelompok' => $results[0]->kelompok_nama,
    ]);
    }

}
