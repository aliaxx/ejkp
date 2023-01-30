<?php

namespace app\components;

use Yii;
use common\models\Peranan;

/**
 * Access Class
 */
class Access extends \yii\base\Component
{
    public $role;
    public $permissions = [];

    public function init()
    {
        parent::init();

        if (!Yii::$app->user->isGuest) {
            $this->role = Yii::$app->user->identity->PERANAN;
            $this->permissions = $this->getUserPermission();
        }
    }

    public function can($permission)
    {
        if ($this->role) {
            if ($this->role == 1) {
                return true;
            } else {
                $access = $this->permissions;
                return (in_array($permission, $access));
            }
        }

        return false;
    }

    public function getUserPermission()
    {
        $permissions = [];
        $model = Peranan::findOne($this->role);

        if (isset($model->perananAkses)) {
            foreach ($model->perananAkses as $item) {
                if ($item->AKSES_LIHAT) $permissions[] = $item->KODAKSES.'-read';
                if ($item->AKSES_URUS) $permissions[] = $item->KODAKSES.'-write';
            }
        }

        return $permissions;
    }

    public function getPermissionList()
    {
        //permission mesti sama seperti di sidenav 'visible access' dan juga access di controller.
        $permissions = [
            'peranan'                =>     'Kawalan Pengguna -> Peranan Pengguna',
            'pengguna'               =>     'Kawalan Pengguna -> Profil Pengguna',
            'subunit'                =>     'Kawalan Pengguna -> Subunit',
            'audit-log'              =>     'Kawalan Pengguna -> Log Audit',
            'param-header'           =>     'Penyelenggaraan -> Parameter Carian-> Carian Kumpulan',
            'param-detail'           =>     'Penyelenggaraan -> Parameter Carian-> Carian Terperinci',
            'perkara'                =>     'Penyelenggaraan -> Parameter Penggredan Premis/Tandas -> Perkara',
            'komponen'               =>     'Penyelenggaraan -> Parameter Penggredan Premis/Tandas -> Komponen',
            'keterangan'             =>     'Penyelenggaraan -> Parameter Penggredan Premis/Tandas -> Keterangan',
            'dun'                    =>     'Penyelenggaraan -> Dun',
            'lokaliti'               =>     'Penyelenggaraan -> Lokaliti',
            'kolam'                  =>     'Penyelenggaraan -> Parameter Bacaan Air Kolam',
            'lesen'                  =>     'Integrasi -> Integrasi Lesen',
            'sewa'                   =>     'Integrasi -> Integrasi Sewa',
            'SMM'                    =>     'Mutu Makanan -> Sampel Makanan',
            'SDR'                    =>     'Mutu Makanan -> Sitaan',
            'HSW'                    =>     'Mutu Makanan -> Handswab',
            'PKK'                    =>     'Mutu Makanan -> Pemeriksaan Kolam',
            'SRT'                    =>     'Pencegahan Vektor -> Semburan Termal(SRT)',
            'ULV'                    =>     'Pencegahan Vektor -> Semburan Kabus Mesin(ULV)',
            'PTP'                    =>     'Pencegahan Vektor -> Pemeriksaan Tempat Pembiakan Aedes(PTP)',
            'LVC'                    =>     'Pencegahan Vektor -> Aktiviti Larvaciding',
            'PTS'                    =>     'Pencegahan Vektor -> Pemeriksaan Tandas',
            'KPN'                    =>     'Peniaga Kecil & Penjaja -> Pemantauan Penjaja',
            'PPM'                    =>     'Premis Makanan -> Pemeriksaan Premis',
            'APB'                    =>     'Premis Makanan -> Anugerah Premis Bersih',
            'laporan-mutu-makanan'   =>     'Laporan -> Mutu Makanan',
            'laporan-vektor'         =>     'Laporan -> Pencegahan Vektor',
            'laporan-penjaja'        =>     'Laporan -> Peniaga Kecil & Penjaja',
            'laporan-premis-makanan' =>     'Laporan -> Premis Makanan',
            ];

        return $permissions;
    }
}
