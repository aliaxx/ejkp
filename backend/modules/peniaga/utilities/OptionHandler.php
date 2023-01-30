<?php

namespace backend\modules\peniaga\utilities;
use backend\modules\peniaga\models\Transgerai;

use Yii;

class OptionHandler extends \common\utilities\OptionHandler
{
    public static function populate($params = [])
    {
        $data = parent::populate($params);

        $data['jenis-premis'] = [
            1 => 'Premis Tunggal',
            2 => 'Gerai/Petak'
        ];

        $data['jenis-pengguna'] = [
            1 => 'Ketua Pasukan',
            2 => 'Ahli Pasukan'
        ];

        $data['tindakan-penguatkuasa'] = [
            1 => 'Tiada tindakan',
            2 => 'Kompaun',
            3 => 'Sitaan',
            4 => 'Notis kesalahan Pertama',
            5 => 'Notis kesalahan Susulan',
            6 => 'Notis Penutupan',
            7 => 'Notis Cadangan',
            8 => 'Syor Batal'

        ];
        // $data['status-pemantauan'] = [
        //     'AB' => 'Ada Berniaga',
        //     'TBS' => 'Tidak berniaga Sendiri',
        //     'TBL' => 'Tidak berniaga langsung',
        //     '0' => 'Kosong',
        //     'CB' => 'Cuti Berniaga (Tarikh Mula Cuti hingga Tarikh Tamat Cuti)'

        // ];

        $data['status-pemantauan'] = [
            Transgerai::KOSONG => 'Kosong',
            Transgerai::AB => 'Ada Berniaga',
            Transgerai::TBS => 'Tidak berniaga Sendiri',
            Transgerai::TBL => 'Tidak berniaga langsung',
            Transgerai::CB => 'Cuti Berniaga (Tarikh Mula Cuti hingga Tarikh Tamat Cuti)'

        ];
        $data['status-gerai'] = [
            1 => 'Baik',
            2 => 'Rosak'
        ];
        $data['perangkap-grease'] = [
            1 => 'Ada',
            2 => 'Tiada'
        ];


        return $data;
    }
}
