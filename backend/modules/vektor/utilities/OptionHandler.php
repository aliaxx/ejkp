<?php

namespace backend\modules\vektor\utilities;

use Yii;

class OptionHandler extends \common\utilities\OptionHandler
{
    public static function populate($params = [])
    {
        $data = parent::populate($params);

        $data['kaw-pembiakan'] = [
            1 => 'Pembiakan Dalam Rumah',
            2 => 'Pembiakan Luar Rumah'
        ];

        $data['jenis-sembur'] = [
            1 => 'Kawalan',
            2 => 'Pencegahan'
        ];

        $data['kat-lokaliti'] = [
            1 => 'Wabak Terkawal',
            2 => 'Wabak Tidak Terkawal',
            3 => 'Hotspot',
            4 => 'Sporadic'
        ];

        $data['kat-lokaliti'] = [
            1 => 'Wabak Terkawal',
            2 => 'Wabak Tidak Terkawal',
            3 => 'Hotspot',
            4 => 'Sporadic'
        ];

        $data['ulv-surveilan'] = [
            1 => 'Permintaan',
            2 => 'Arahan',
            3 => 'Aduan',
            4 => 'Lain-lain'
        ];

        $data['ptp-surveilan'] = [
            1 => 'Rutin/Berjadual',
            2 => 'Aduan Awam/Arahan',
            3 => 'Spot Check/Quality Control',
            4 => 'Lain-lain'
        ];

        $data['hujan'] = [
            1 => 'Ada',
            2 => 'Tiada',
        ];

        $data['keadaan-hujan'] = [
            1 => 'Tiada',
            2 => 'Rintik-Rintik',
            3 => 'Lebat',
        ];

        $data['angin'] = [
            1 => 'Ada',
            2 => 'Tiada',
        ];

        $data['keadaan-angin'] = [
            1 => 'Tiada',
            2 => 'Sederhana',
            3 => 'Kencang',
        ];

        $data['tempoh'] = [
            1 => '24 Jam',
            2 => '> 24 Jam',
        ];

        $data['aktiviti'] = [
            1 => 'Spraycan',
            2 => 'Mistblower',
            3 => 'Abating'
        ];

        $data['jenis-tindakan'] = [
            1 => 'Kompaun',
            2 => 'Notis',
        ];

        return $data;
    }
}
