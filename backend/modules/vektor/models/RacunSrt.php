<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * This is the model class for table "{{%RACUN_ST}}".
 *
 * @property float $ID
 * @property string $NOSIRI ST
 * @property int $ID_PENGGUNAANRACUN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 3
 * @property int $ID_JENISRACUNSRTULV TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 10
 * @property string|null $ID_JENISPELARUT TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 4
 * @property int|null $BILCAJ
 * @property int|null $BILMESIN
 * @property float|null $AMAUNRACUN
 * @property float|null $AMAUNPELARUT
 * @property float|null $AMAUNPETROL
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class RacunSrt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%RACUN_SRT}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'ID_PENGGUNAANRACUN', 'ID_JENISRACUNSRTULV'], 'required'],
            [['BILCAJ', 'BILMESIN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI'], 'string', 'max' => 20],
            [['ID_PENGGUNAANRACUN', 'ID_JENISRACUNSRTULV', 'ID_JENISPELARUT', 'AMAUNRACUN', 'AMAUNPELARUT', 'AMAUNPETROL'], 'safe'],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NOSIRI' => 'ST',
            'ID_PENGGUNAANRACUN' => 'Penggunaan Racun',
            'ID_JENISRACUNSRTULV' => 'Jenis Racun Serangga',
            'ID_JENISPELARUT' => 'Jenis Pelarut',
            'BILCAJ' => 'Bil Caj',
            'BILMESIN' => 'Bil Mesin',
            'AMAUNRACUN' => 'Amaun Racun',
            'AMAUNPELARUT' => 'Amaun Pelarut',
            'AMAUNPETROL' => 'Amaun Petrol',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini',
        ];
    }

    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
    }

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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPenggunaanRacun()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_PENGGUNAANRACUN'])
        ->onCondition(['KODJENIS' => '25']);    
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisRacun()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISRACUNSRTULV'])
        ->onCondition(['KODJENIS' => '10']);    
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPelarut()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISPELARUT'])
        ->onCondition(['KODJENIS' => '16']);    
    }


}
