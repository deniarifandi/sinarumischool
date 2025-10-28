<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\PersonelModel;
use App\Libraries\datatable;
use Config\Database;

class Personel extends MyResourceController
{

    public $table = "Personel";
    public $title = "Personel";
    public $primaryKey = "guru_id";

    public $fieldList = [
        ['guru_nama','Name'],
        ['kkbnomor','No. Nomor'],
        ['kkb','Masa KKB'],
        ['kkbstart','Tanggal Mulai KKB'],
    ];

    public $selectList= [
        'Personel.*',
        // 'Divisi.divisi_nama'
    ];

    public $toSearch = 
    [
        'Personel.guru_nama',
        // 'Divisi.divisi_nama'
    ];

    public $where = [
        // 'Divisi.divisi_id !=' => '0'
    ];


    public $joinTable = [
        // ['Divisi','Divisi.divisi_id = Personel.divisi_id','left']
    ];

    public $field = [
        ['text','guru_nama'],
        // ['select','divisi_id'],
        ['text','guru_username'],
        // ['text','guru_jabatan'],
        ['password','guru_password'],
        ['select','kkb'],
        ['text','bpjskesehatan'],
        ['text','bpjsketenagakerjaan'],
        ['separator','guru_jabatan'],
        ['text','placebirth'],
        ['date','datebirth'],
        ['select','gender'],
        ['select','religion'],
        ['select','marital'],
        ['select','lasteducation'],
        ['text','phone'],
        ['text','address'],
        ['file','filekk'],
        ['file','filektp'],
        ['separator','guru_jabatan'],
        ['number','trainingperiod'],
        ['date','trainingstart'],
        ['select','trainingdivisi'],
        ['select','trainingtrainer'],
        ['select','trainingmengetahui'],
        ['separator','guru_jabatan'],
        ['text','kkbnomor'],
        ['date','kkbstart'],
        ['file','arsip']
    ];


    public $fieldName = [
        'Name',
        'Username',
        'Password',
        'Masa KKB',
        'No. BPJS Kesehatan',
        'No. BPJS Ketenagakerjaan',
        '-----PERSONAL-----',
        'Tempat Lahir',
        'Tanggal Lahir',
        'Gender',
        'Agama',
        'Status Pernikahan',
        'Pendidikan Terakhir',
        'HP',
        'Alamat',
        'File KK',
        'File KTP',
        '-----TRAINING-----',
        'TRAINING - Periode (Dalam Bulan)',
        'TRAINING - Tanggal Mulai',
        'TRAINING - Divisi',
        'TRAINING - Trainer',
        'TRAINING - Mengetahui',
         '-----KKB-----',
        'Nomor KKB',
        'Tanggal Mulai KKB',
        'Arsip Bertanda Tangan'
      
    ];

    public $fieldOption = [
        ['noOption'],
        ['noOption'],
        ['noOption'],
        [['1 Tahun','1 Tahun'],['3 Tahun','3 Tahun'],['3 Tahun','3 Tahun'],['9 Tahun','9 Tahun'],],
        ['noOption'],
        ['noOption'],
          ['noOption'],
        ['noOption'],
         ['noOption'],
         [['Male','Male'],['Female','Female'],],
         [['Moslem','Moslem'],['Christian','Christian'],['Catholic','Catholic'],['Hindu','Hindu'],['Buddha','Buddha'],],
         [['Single','Single'],['Married','Married'],],
         [['S3','S3'],['S2','S2'],['S1','S1'],['D4','D4'],['D3','D3'],['D2','D2'],['D1','D1'],['SMA Sederajat','SMA Sederajat'],['SMP Sederajat','SMP Sederajat'],['SD Sederajat','SD Sederajat'], ['TK Sederajat','TK Sederajat'],],
         ['noOption'],
         ['noOption'],
         ['noOption'],
         ['noOption'],
         ['noOption'],
         ['noOption'],
         ['noOption'],
         ['noOption'],
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
        $this->model = new PersonelModel();
         $this->fieldOption[20] = $this->getdata('Divisi'); 
          $this->fieldOption[21] = $this->getdataguru('Guru'); 
           $this->fieldOption[22] = $this->getdataguru('Guru'); 
        if (session()->get('guru_id')!= 0) {
            $this->where = 'guru_id ='.session()->get('guru_id');    
        }
        
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function getdataguru($table){
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        $builder->select('*');
        $builder->where('deleted_at', null);
        $builder->orderBy('guru_nama','asc');
        $query = $builder->get();
        $result = $query->getResultArray();
        $indexedOnly = array_map('array_values', $result);

        // print_r($indexedOnly);
    
        return $indexedOnly;
    }

    public function print(){

        $db = \Config\Database::connect();
        $builder = $db->table('Personel');
        $builder->select('*');
        $builder->where('deleted_at', null);

        $query = $builder->get();

        return view('/report/guru_print',['data' => $query->getResult()]);

    }


   

    public function sendConfirm($nama){

    $token = 'EAAcbbjv93u0BPPdS3swcVVVzBJwkrn2oLGZABP1JMZBnd7XQqyZCwE6Fa3kK1L6QFfU7WvhHXTDT32IgLY0HvFCxn0ZAZBtn1iexZAiiKkKa9y0Ve5vhJNXvpZCBNqwmHvkb9mQh7mmisCp7cnrL9KoukWGt2CrrDIajwfPkA6f8ZB8HIWm4MJ0IwEW9ZBmmGXTIjOQZDZD'; // Replace with your valid token
    $phone_number_id = '718496908011318'; // From WhatsApp Business Account
    $recipient_number = '+6281235817488'; // Use full international format

    $url = "https://graph.facebook.com/v19.0/$phone_number_id/messages";

    $data = [
        "messaging_product" => "whatsapp",
        "to" => $recipient_number,
        "type" => "template",
        "template" => [
            "name" => "attendance_confirmation",
            "language" => [
                "code" => "en_US"
            ],
            "components" => [
                [
                    "type" => "body",
                    "parameters" => [
                        [ "type" => "text", "text" => "Attendance" ],
                        [ "type" => "text", "text" => session()->get('nama') ],
                        [ "type" => "text", "text" => "My Little Island School" ],
                        [ "type" => "text", "text" => "15 July 2025" ]
                    ]
                ]
            ]
        ]
    ];

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch);
    } else {
        echo "Response:\n$response";
    }

    curl_close($ch);
    }
}

