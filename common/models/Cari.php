<?php

namespace common\models;

use Yii;
use yii\base\model;


class Cari extends \yii\db\ActiveRecord
{
    public $nama_pengguna;
    public $jenis_carian;

    public function rules()
    {
        return [
            [['nama_pengguna','jenis_carian'], 'required', 'on' => ['cari']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'nama_pengguna' => 'Nama Pengguna / Nokp Pengguna',
            'jenis_carian' => 'Jenis Carian',
        ];
    }

}
