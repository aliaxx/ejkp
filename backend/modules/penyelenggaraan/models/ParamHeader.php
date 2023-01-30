<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "{{%parameter_header}}".
 *
 * @property int  kodjenis'*Kod Jenis*
 * @property string $prgn *Keterangan*
 * @property int $status *Status*\n1 = aktif\n2 = tidak aktif
 * @property int $PGNDAFTAR *ID Pengguna Daftar*
 * @property int $TRKHDAFTAR *Tarikh Daftar*
 * @property int $pgnakhir *ID Pengguna Kemaskini Terakhir*
 * @property int $trkhakhir *Tarikh Kemaskini Terakhir*
 *
 * @property Atr[] $atrs
 * @property Kpp[] $kpps
 */
class ParamHeader extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PARAMETER_HEADER}}';
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
            [['PRGN'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['PRGN'], 'string', 'max' => 150],
            [['KODJENIS'], 'unique', 'targetAttribute' => ['KODJENIS']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'KODJENIS' => 'Kod Kumpulan',
            'PRGN' => 'Keterangan Kumpulan',
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
