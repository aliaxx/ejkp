<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%peranan}}".
 *
 * @property int $idperanan
 * @property string $namaperanan
 * @property int $status
 * @property int $pgndaftar
 * @property int $trkhdaftar
 * @property int $pgnakhir
 * @property int $trkhakhir
 *
 * @property PerananAkses[] $perananAkses
 */
class Peranan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PERANAN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAMAPERANAN'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NAMAPERANAN'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IDPERANAN' => 'ID Peranan',
            'NAMAPERANAN' => 'Nama Peranan',
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
    public function getPerananAkses()
    {
        return $this->hasMany(PerananAkses::className(), ['IDPERANAN' => 'IDPERANAN']);
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
