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
        // ['divisi_nama','Divison'],
        // ['guru_jabatan','Position']
// ['guru_password','Password']
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
        ['password','guru_password']
    ];


    public $fieldName = [
        'Name',
        // 'Division',
        'Username',
        // 'Jabatan',
        'Password'
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
        $this->model = new PersonelModel();
        if (session()->get('guru_id')!= 0) {
            $this->where = 'guru_id ='.session()->get('guru_id');    
        }
        
        $this->dataToShow = $this->prepareDataToShow();
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

