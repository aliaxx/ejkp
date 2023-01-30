<?php

namespace backend\modules\premis\models;

use Yii;

/**
 * This is the model class for table "TBANUGERAH".
 *
 * @property int $NOLESEN
 * @property string $NOSYARIKAT
 * @property string|null $TAHUN
 * @property string|null $GRED
 * @property string|null $CATATAN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Anugerah extends \yii\db\ActiveRecord
{
    public $NOLESEN , $NOLESEN1, $NOSSM, $NAMASYARIKAT, $NAMAPREMIS;
    public $ALAMAT1, $ALAMAT2, $ALAMAT3, $POSKOD, $NOTEL, $NAMAPEMOHON, $NOKPPEMOHON;
    public $KATEGORILESEN, $JENIS_PREMIS,$JENISJUALAN, $KETERANGANKATEGORI;
    public $KUMPULAN_LESEN, $KETERANGAN_KUMPULAN;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TBANUGERAH';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOLESEN', 'NOSYARIKAT'], 'required'],
            [['NOLESEN', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSYARIKAT', 'TAHUN', 'GRED', 'CATATAN'], 'string', 'max' => 45],
            [['NOLESEN', 'NOSYARIKAT'], 'unique', 'targetAttribute' => ['NOLESEN', 'NOSYARIKAT']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NOLESEN' => 'Nolesen',
            'NOSYARIKAT' => 'Nosyarikat',
            'TAHUN' => 'Tahun',
            'GRED' => 'Gred',
            'CATATAN' => 'Catatan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
        ];
    }

    public function getPremis0() 
    {
        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getPemilik0() 
    {
        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI'])->via('premis0');
    }
}
