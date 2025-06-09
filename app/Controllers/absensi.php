<?php

namespace App\Controllers;

use Config\Database;

class absensi extends BaseController
{
    
    public function __construct()
    {
      
    
    }

    public function index(){
        $user =  session()->get('user_id');
        $builder = Database::connect()->table('murid');
        $builder->select('absensi.*, murid.*, kelompok.*');
        $builder->join('absensi','murid.murid_id = absensi.murid_id');
        $builder->join('kelompok','kelompok.kelompok_id = murid.kelompok_id','left');
        $builder->join('guru','guru.guru_id = kelompok.guru_id','left');
        $builder->where('guru.guru_id',$user);
        $builder->groupBy('tanggal');

        // print_r($builder->get()->getResult());
        $data = $builder->get()->getResult();
        return view('mli/listAbsensi',['data' => $data]);
    }

    public function addAbsensi(){
        $user =  session()->get('user_id');
        $builder = Database::connect()->table('murid');
        $builder->select('murid.*, kelompok.*');
        $builder->join('kelompok','kelompok.kelompok_id = murid.kelompok_id','left');
        $builder->join('guru','guru.guru_id = kelompok.guru_id','left');
        $builder->where('guru.guru_id',$user);
        // print_r($builder->get()->getResult());
        $data = $builder->get()->getResult();
        return view('mli/addAbsensi',['data' => $data]);
    }

    public function editAbsensi($date)
    {
        $user =  session()->get('user_id');

        $db = \Config\Database::connect();
        $builder = $db->table('absensi');
        
         $builder->select('absensi.*, murid.*, kelompok.*');
        $builder->join('murid', 'murid.murid_id = absensi.murid_id');
        $builder->join('kelompok', 'kelompok.kelompok_id = murid.kelompok_id');
        $builder->join('guru','guru.guru_id = kelompok.guru_id','left');
        $builder->where('tanggal', $date);
        $builder->where('guru.guru_id',$user);

        $data['absensi'] = $builder->get()->getResult();
        $data['tanggal'] = $date;

        return view('mli/editAbsensi', ['data' => $data]);
    }

  public function saveAbsensi()
{

    // echo $this->request->getPost('date');;
    $db = \Config\Database::connect();
    $builder = $db->table('absensi'); // your attendance table name

    $murid_ids = $this->request->getPost('murid_id');      // array of student IDs
    $attendances = $this->request->getPost('attendance');  // array of attendance statuses
    $date = $this->request->getPost('date'); // or get from form input if needed

    for ($i = 0; $i < count($murid_ids); $i++) {
        // Prepare data for each student
        $data = [
            'murid_id' => $murid_ids[$i],
            'status'   => $attendances[$i], // 1 = present, 2 = absent, 3 = sick
            'tanggal'  => $date,
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

}
