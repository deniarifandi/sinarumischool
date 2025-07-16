<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class Gurudivisi extends BaseController
{

    public function __construct()
    {   
        $this->model = new GuruModel();
    }


    public function index(){
        echo view('/gurudivisi/index');
    }

    public function edit($id){


        $builder = Database::connect()->table("Guru");
        $builder->select('*');
        $builder->where('Guru.guru_id',$id);
        $builder->where('Guru.deleted_at',NULL);
        $data = $builder->get()->getResult();
        
        $builder = Database::connect()->table("Divisi");
        $builder->select('*');
        // $builder->join('Guru','Guru.guru_id = Gurudivisi.guru_id');
        $builder->where('Divisi.deleted_at',NULL);
        $divisi = $builder->get()->getResult();

        $assigned = Database::connect()->table('Gurudivisi')
        ->select('divisi_id')
        ->where('guru_id', $id)
        ->get()->getResultArray(); // gives: [ [divisi_id => 1], [divisi_id => 3], ... ]

        $assignedDivisi = array_column($assigned, 'divisi_id'); // gives: [1, 3, 5]

        return view('gurudivisi/create',[
            'data' => $data, 
            'divisiList' => $divisi,
            'assignedDivisi' => $assignedDivisi
        ]);
    }

    public function data(){
        $builder = Database::connect()->table("Guru");

        $builder->select(implode(', ', $this->selectList));
      
        $builder->where('Guru.deleted_at',NULL);;

        $datatable = new Datatable();

        return $datatable->generate($builder, "Guru.guru_id", $this->toSearch);
    }

    public function submit()
    {
        $selectedDivisi = $this->request->getPost('divisi'); // will be an array

        // Example: print selected
        print_r($selectedDivisi);

        // Example: loop
        foreach ($selectedDivisi as $divisi_id) {
            // do something with each selected divisi_id
        }

        // Redirect or return view
    }

    public function toggle()
    {
        $data = $this->request->getJSON(true);

        $guru_id   = $data['guru_id'];
        $divisi_id = $data['divisi_id'];
        $status    = $data['status']; // 1 = checked, 0 = unchecked

        $db = \Config\Database::connect();
        $table = $db->table('Gurudivisi');

        if ($status) {
            // Insert if not exists
            $exists = $table->where([
                'guru_id' => $guru_id,
                'divisi_id' => $divisi_id
            ])->countAllResults();

            if (!$exists) {
                $table->insert([
                    'guru_id' => $guru_id,
                    'divisi_id' => $divisi_id
                ]);
            }
        } else {
            // Remove
            $table->where([
                'guru_id' => $guru_id,
                'divisi_id' => $divisi_id
            ])->delete();
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

}

