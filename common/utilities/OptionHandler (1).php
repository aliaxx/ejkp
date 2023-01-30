<?php
namespace common\utilities;

use Yii;

class OptionHandler extends \codetitan\handlers\OptionHandler
{
    const STATUS_AKTIF = 1;
    const STATUS_TIDAK_AKTIF = 2;

    public static function populate($params = [])
    {
        $data = parent::populate($params);

        $data['STATUS'] = [
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
        ];
        $data['PENGGUNA_APPS'] = [
            1 => 'Ya',
            2 => 'Tidak',
        ];

        $data['ya-tidak'] = [
            1 => 'Ya',
            2 => 'Tidak',
        ];

        $data['premis-tandas'] = [
            1 => 'Premis',
            2 => 'Tandas',
        ];

        $data['status-lesen'] = [
            'D' => 'Keputusan Permohonan Pra Kelulusan',
            'G' => 'Keputusan Permohonan Ditolak',
            'K' => 'Keputusan Permohonan Tidak Lengkap',
            'L' => 'Keputusan Permohonan Lulus',
            'P' => 'Terima Permohonan',
            'T' => 'Keputusan Permohonan Ditangguhkan',
            'X' => 'Permohonan telah dibatalkan',
        ];

        $data['JENIS'] = [
            'L' => 'Lelaki',
            'P' => 'Perempuan',
        ];

        $data['DATA_FILTER'] = [
            1 => 'Semua',
            2 => 'Negeri',
            3 => 'Cawangan',
        ];

        $data['log-status'] = [
            1 => 'Info',
            2 => 'Warning',
            3 => 'Danger',
        ];

        $data['log-tindakan'] = [
            1 => 'create',
            2 => 'update',
            3 => 'delete',
            4 => 'upload',
            5 => 'delete upload',
        ];

        $data['DATA_FILTER'] = [
            1 => 'Semua',
            2 => 'Subunit',
            // 3 => 'Cawangan',
        ];

        $data['kots'] = [
            1 => 'Ya',
            2 => 'Tidak',
        ];

        $data['jenis-premis'] = [
            1 => 'Premis Tunggal',
            2 => 'Gerai/Petak'
        ];

        $data['jenis-pengguna'] = [
            1 => 'Ketua Pasukan',
            2 => 'Ahli Pasukan'
        ];

        $data['status-lesen-premis'] = [
            '1' => 'Ada', '2' => 'Tiada',
        ];

        $data['jenis-carian'] = [
            '1' => 'Lesen',
            '2' => 'Sewa',
        ];
        
        $data['PERANAN']['options'] = function() {
            $source = \common\models\Peranan::find()->where(['STATUS' => 1])->orderBy(['NAMAPERANAN' => SORT_ASC])->all();
            return \yii\helpers\ArrayHelper::map($source, 'IDPERANAN', 'NAMAPERANAN');
        };

        return $data;
    }
}