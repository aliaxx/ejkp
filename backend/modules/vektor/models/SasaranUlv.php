<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * This is the model class for table "{{%SASARAN_ULV}}".
 *
 * @property float $ID
 * @property string|null $NOSIRI
 * @property int|null $ID_JENISPREMIS
 * @property int|null $SASARAN1
 * @property int|null $PENCAPAIAN1
 * @property int|null $SASARAN2
 * @property int|null $PENCAPAIAN2
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class SasaranUlv extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SASARAN_ULV}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'required'],
            [['ID'], 'number'],
            [['ID_JENISPREMIS', 'SASARAN1', 'PENCAPAIAN1', 'SASARAN2', 'PENCAPAIAN2', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'safe'],
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
            'SASARAN1' => 'Sasaran1',
            'PENCAPAIAN1' => 'Pencapaian1',
            'SASARAN2' => 'Sasaran2',
            'PENCAPAIAN2' => 'Pencapaian2',
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
        ->onCondition(['KODJENIS' => '4']);    
        // var_dump($this->ID_JENISPREMIS);
        // exit();
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getJumSasaran()
    {
        // $sql = "Select DESIGNATION from C##MAJLIS.PRUSER where USERID  =  '$this->IDPENGGUNA'";
        // $get_data = \Yii::$app->db->createCommand($sql)->queryScalar();
        $sasaran = Yii::$app->db->createCommand("SELECT SASARAN1 + SASARAN2 FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'
        AND ID_JENISPREMIS = '$this->ID_JENISPREMIS' ")->queryScalar();

        $pencapaian = Yii::$app->db->createCommand("SELECT PENCAPAIAN1 + PENCAPAIAN2 FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'
        AND ID_JENISPREMIS = '$this->ID_JENISPREMIS' ")->queryScalar();


        $sasaran1 = Yii::$app->db->createCommand("SELECT SUM(SASARAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $pencapaian1 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN1) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $sasaran2 = Yii::$app->db->createCommand("SELECT SUM(SASARAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();
        $pencapaian2 = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();

        $sumsasaran = Yii::$app->db->createCommand("SELECT SUM(SASARAN1) + SUM(SASARAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();

        $sumpencapaian = Yii::$app->db->createCommand("SELECT SUM(PENCAPAIAN1) + SUM(PENCAPAIAN2) FROM TBSASARAN_ULV WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();

        // $get_datas = $get_data[0]['DESIGNATION'];
        // return $get_data;

        $data = [];
        $data['sasaran']=$sasaran;
        $data['pencapaian']=$pencapaian;
        $data['sasaran1']=$sasaran1;
        $data['pencapaian1']=$pencapaian1;
        $data['sasaran2']=$sasaran2;
        $data['pencapaian2']=$pencapaian2;
        $data['sumsasaran']=$sumsasaran;
        $data['sumpencapaian']=$sumpencapaian;

        return $data;

    }


}
