<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "TBKESALAHAN".
 *
 * @property string $KODAKTA
 * @property int $KODSALAH
 * @property string|null $SEKSYEN
 * @property string|null $PRGNSEKSYEN
 * @property string|null $PRGN1
 * @property string|null $PRGN2
 * @property string|null $PRGNDENDA
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class SeksyenKesalahan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%KESALAHAN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['KODAKTA', 'KODSALAH'], 'required'],
            [['KODSALAH', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['KODAKTA'], 'string', 'max' => 20],
            [['SEKSYEN', 'PRGNSEKSYEN', 'PRGN1', 'PRGN2'], 'string', 'max' => 255],
            [['PRGNDENDA'], 'string', 'max' => 500],
            [['KODAKTA', 'KODSALAH'], 'unique', 'targetAttribute' => ['KODAKTA', 'KODSALAH']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'KODAKTA' => 'Kod Akta',
            'KODSALAH' => 'Kod Salah',
            'SEKSYEN' => 'Seksyen',
            'PRGNSEKSYEN' => 'Keterangan Seksyen',
            'PRGN1' => 'Keterangan 1',
            'PRGN2' => 'Keterangan 2',
            'PRGNDENDA' => 'Keterangan Denda',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'ID Pengguna Kemaskini Terakhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAkta() //join table with table Akta
    {
        return $this->hasOne(\backend\modules\penyelenggaraan\models\Akta::className(), ['KODAKTA' => 'KODAKTA']); //one-to-one(1 header hanya ada 1 detail)
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
