<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "TBZON_AHLIMAJLIS".
 *
 * @property string $PRGNPANJANGZON
 * @property string $NAMAAHLIMAJLIS
 * @property string $PENGGAL
 * @property string $PRGNPANJANG
 * @property float $STATUS
 * @property float $ID
 * @property int $PGNDAFTAR
 * @property int $TRKHDAFTAR
 * @property int $PGNAKHIR
 * @property int $TRKHAKHIR
 */
class ZonAhliMajlis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TBZON_AHLIMAJLIS';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PRGNZON', 'NAMAAHLIMAJLIS', 'PENGGAL', 'PRGNPANJANG', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'required'],
            [['STATUS', 'ID'], 'number'],
            [['PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['PRGNZON'], 'string', 'max' => 200],
            [['NAMAAHLIMAJLIS', 'PRGNPANJANG'], 'string', 'max' => 200],
            [['PENGGAL'], 'string', 'max' => 20],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PRGNZON' => 'Kod Zon',
            'NAMAAHLIMAJLIS' => 'Nama Ahli Majlis',
            'PENGGAL' => 'Penggal',
            'PRGNPANJANG' => 'Keterangan',
            'STATUS' => 'Status',
            'ID' => 'ID',
            'PGNDAFTAR' => 'ID Pendaftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => '	ID Pengguna Kemaskini Terakhir ',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
        ];
    }


     /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNDAFTAR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNAKHIR']);
    }
}
