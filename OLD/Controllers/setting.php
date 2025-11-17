<?php

namespace App\Controllers;

//use CodeIgniter\RESTful\ResourceController;
use App\Models\SettingModel;
use App\Libraries\datatable;
use Config\Database;

class setting extends MyResourceController
{

    public $table = "setting";
    public $title = "Setting";
    public $primaryKey = "setting_id";
    public $fieldList = [
        ['sekolah','Nama Sekolah'],
        ['kepala','Kepala Sekolah'],
        ['b','Pembiasaan Pagi'],
        ['c','Kegiatan Pembuka'],
        ['e','Istirahat'],
        ['f','Penutup'],
        ['g','Penilaian']

    ];

    public $field = [
        ['text','sekolah'],
        ['text','kepala'],
        ['textarea','b'],
        ['textarea','c'],
        ['textarea','d1'],
        ['textarea','d2'],
        ['textarea','d3'],
        ['textarea','d4'],
        ['textarea','d5'],
        ['textarea','e'],
        ['textarea','f'],
        ['textarea','g']
];

public $fieldName = 
        ['Nama Sekolah',
        'Kepala Sekolah',
        'Pembiasaan Pagi',
        'Kegiatan Pembuka',
        'Inti Senin',
        'Inti Selasa',
        'Inti Rabu',
        'Inti Kamis',
        'Inti Jumat',
        'Istirahat',
        'Penutup',
        'Penilaian']
    ;

public $fieldOption = [
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
        ['noOption'],
        ['noOption']
];

    public $dataToShow = [];


    public function __construct()
    {
        $this->model = new settingModel();
        $this->dataToShow = $this->prepareDataToShow();
    }

    public function data(){
        $builder = Database::connect()->table($this->table)
        ->select('setting.*')
        ->where('setting.deleted_at',NULL);

        $datatable = new Datatable();

        return $datatable->generate($builder, 'setting.setting_id',[
            'setting.kepala'
        ]);
    }

}
