<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * This is the model class for table "TBLVC_AKTIVITI".
 *
 * @property int $ID
 * @property string|null $NOSIRI
 * @property int|null $V_SASARANPREMIS Sasaran Premis
 * @property int|null $V_BILPREMIS Bil. Premis
 * @property int|null $V_BILBEKAS Bil. Bekas
 * @property int|null $V_ID_JENISRACUN Jenis Racun
 * @property int|null $V_JUMRACUN Jum. Racun
 * @property int|null $V_BILMESIN Bil. Mesin
 * @property int|null $PGNDAFTAR Pengguna Daftar
 * @property int|null $TRKHDAFTAR Tarikh Daftar
 * @property int|null $PGNAKHIR Pengguna Akhir
 * @property int|null $TRKHAKHIR Tarikh Akhir
 */
class Lvcaktiviti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%LVC_AKTIVITI}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID', 'V_SASARANPREMIS', 'V_BILPREMIS', 'V_BILBEKAS', 'V_ID_JENISRACUN', 'V_JUMRACUN', 'V_BILMESIN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'AKTIVITI'], 'safe'],
            [['NOSIRI'], 'string', 'max' => 20],
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
            'NOSIRI' => 'Nosiri',
            'AKTIVITI' => 'Aktiviti',
            'V_SASARANPREMIS' => 'Sasaran Premis',
            'V_BILPREMIS' => 'Bil. Premis',
            'V_BILBEKAS' => 'Bil. Bekas',
            'V_ID_JENISRACUN' => 'Jenis Racun',
            'V_JUMRACUN' => 'Jum. Racun',
            'V_BILMESIN' => 'Bil. Mesin',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
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

    public function getRacun()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'V_ID_JENISRACUN'])
            ->onCondition(['KODJENIS' => 10]);
    }

}
