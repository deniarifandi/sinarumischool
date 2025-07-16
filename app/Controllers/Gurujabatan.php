<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\GuruModel;
use App\Libraries\datatable;
use Config\Database;

class Gurujabatan extends BaseController
{

    public function __construct()
    {   
        $this->model = new GuruModel();
    }


    public function index(){
        echo view('/gurujabatan/index');
    }

    public function edit($id){


        $builder = Database::connect()->table("Guru");
        $builder->select('*');
        $builder->where('Guru.guru_id',$id);
        $builder->where('Guru.deleted_at',NULL);
        $data = $builder->get()->getResult();
        
        $builder = Database::connect()->table("Jabatan");
        $builder->select('*');
        // $builder->join('Guru','Guru.guru_id = Gurudivisi.guru_id');
        $builder->where('Jabatan.deleted_at',NULL);
        $jabatan = $builder->get()->getResult();

        $assigned = Database::connect()->table('Gurujabatan')
        ->select('jabatan_id')
        ->where('guru_id', $id)
        ->get()->getResultArray(); // gives: [ [divisi_id => 1], [divisi_id => 3], ... ]

        $assignedJabatan = array_column($assigned, 'jabatan_id'); // gives: [1, 3, 5]

        // print_r(expression)

        return view('gurujabatan/create',[
            'data' => $data, 
            'jabatanList' => $jabatan,
            'assignedJabatan' => $assignedJabatan
        ]);
    }

    public function data(){
        $builder = Database::connect()->table("Guru");

        $builder->select(implode(', ', $this->selectList));
      
        $builder->where('Guru.deleted_at',NULL);;

        $datatable = new Datatable();

        return $datatable->generate($builder, "Guru.guru_id", $this->toSearch);
    }


    public function toggle()
    {
        $data = $this->request->getJSON(true);

        $guru_id   = $data['guru_id'];
        $jabatan_id = $data['jabatan_id'];
        $status    = $data['status']; // 1 = checked, 0 = unchecked

        $db = \Config\Database::connect();
        $table = $db->table('Gurujabatan');

        if ($status) {
            // Insert if not exists
            $exists = $table->where([
                'guru_id' => $guru_id,
                'jabatan_id' => $jabatan_id
            ])->countAllResults();

            if (!$exists) {
                $table->insert([
                    'guru_id' => $guru_id,
                    'jabatan_id' => $jabatan_id
                ]);
            }
        } else {
            // Remove
            $table->where([
                'guru_id' => $guru_id,
                'jabatan_id' => $jabatan_id
            ])->delete();
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

}

