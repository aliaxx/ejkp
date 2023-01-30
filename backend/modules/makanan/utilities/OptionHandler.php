<?php

namespace backend\modules\makanan\utilities;

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
            6 => 'Notis Penutupan'

        ];
        $data['status-pemantauan'] = [
            1 => 'AB - Ada Berniaga',
            2 => 'TBS - Tidak berniaga Sendiri',
            3 => 'TBL - Tidak berniaga langsung',
            4 => '0 - Kosong',
            5 => 'CB - Cuti Berniaga (Tarikh Mula Cuti hingga Tarikh Tamat Cuti)'

        ];
        $data['status-gerai'] = [
            1 => 'Baik',
            2 => 'Rosak'
        ];
        $data['perangkap-grease'] = [
            1 => 'Ada',
            2 => 'Tiada'
        ];
        $data['jenisrawatan'] = [
            1 => 'Pengedosan Manual',
            2 => 'Pengedosan Auto'
        ];
        $data['jantina'] = [
            1 => 'Lelaki',
            2 => 'Perempuan'
        ];

        $data['ty2-fhc'] = [
            1 => 'Ada',
            2 => 'Tiada'
        ];

        $data['keputusan'] = [
            1 => 'Positif',
            2 => 'Negatif'
        ];
        return $data;
    }
}
