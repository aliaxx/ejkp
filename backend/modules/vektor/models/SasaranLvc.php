<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;


/**
 * This is the model class for table "{{%SASARAN_PTP}}".
 *
 * @property float $ID
 * @property string|null $NOSIRI
 * @property int|null $ID_JENISPREMIS
 * @property int|null $SASARAN
 * @property int|null $PENCAPAIAN
 * @property int|null $JUMPOSITIF
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class SasaranLvc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SASARAN_LVC}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'number'],
            [['ID_JENISPREMIS', 'SASARAN', 'PENCAPAIAN', 'JUMPOSITIF', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
            'ID_JENISPREMIS' => 'Id  Jenispremis',
            'SASARAN' => 'Sasaran',
            'PENCAPAIAN' => 'Pencapaian',
            'JUMPOSITIF' => 'Jumpositif',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
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
     * @return yii\db\ActiveQuery
     */
    public function getPremis()
    {
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI']);  

    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPremis1()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISPREMIS'])
        ->onCondition(['KODJENIS' => '8']);    
        // var_dump($this->ID_JENISPREMIS);
        // exit();
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getJumSasaran()
    {
        $sasaran = Yii::$app->db->createCommand("SELECT SUM(SASARAN) FROM TBSASARAN_LVC WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $pencapaian = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN) FROM TBSASARAN_LVC WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $positif = Yii::$app->db->createCommand("SELECT SUM(JUMPOSITIF) FROM TBSASARAN_LVC WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $peratusan = Yii::$app->db->createCommand("SELECT (PENCAPAIAN/(SASARAN+PENCAPAIAN))*100 FROM TBSASARAN_LVC WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();

        $data = [];
        $data['sasaran']=$sasaran;
        $data['pencapaian']=$pencapaian;
        $data['positif']=$positif;
        $data['peratusan']= number_format($peratusan, 2);

        return $data;

    }

}
