<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "{{%parameter_detail}}".
 *
 * @property int $KODJENIS *Kod Jenis*
 * @property int $KODDETAIL *Kod Tindakan*
 * @property string $PRGN *Keterangan*
 * @property int $status *Status*\n1 = aktif\n2 = tidak aktif
 * @property int $PGNDAFTAR *ID Pengguna Daftar*
 * @property int $TRKHDAFTAR *Tarikh Daftar*
 * @property int $pgnakhir *ID Pengguna Kemaskini Terakhir*
 * @property int $TRKHAKHIR *Tarikh Kemaskini Terakhir*
 */
class ParamDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PARAMETER_DETAIL}}';
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
            [['KODJENIS', 'KODDETAIL', 'PRGN'], 'required'],
            [['KODJENIS', 'KODDETAIL', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['PRGN'], 'string', 'max' => 250],
            [['KODJENIS', 'KODDETAIL'], 'unique', 'targetAttribute' => ['KODJENIS', 'KODDETAIL']],
        ];
    }

    /**
     * {@inheritdoc}
     * attribute di db => label yang display di form
     */
    public function attributeLabels()
    {
        return [ 
            'KODJENIS' => 'Kod Kumpulan',
            'KODDETAIL' => 'Kod Terperinci',
            'PRGN' => 'Keterangan Terperinci',
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
    public function getParamHeader() //join table with table header & table detail
    {
        return $this->hasOne(ParamHeader::className(), ['KODJENIS' => 'KODJENIS']); //one-to-one(1 header hanya ada 1 detail)
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriPremis() //join table with table header & table detail
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'KODETAIL_KATTANDAS']); //one-to-one(1 header hanya ada 1 detail)
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
