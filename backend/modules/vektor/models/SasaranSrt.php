<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * This is the model class for table "{{%SASARAN_ST}}".
 *
 * @property float $ID
 * @property string $NOSIRI ST
 * @property int $ID_JENISPREMIS TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 8
 * @property int|null $JUMPREMIS jumlah premis 200 m
 * @property int|null $PENCAPAIAN1 pencapaian 200m
 * @property int|null $PENCAPAIAN2 pencapaian < 200m
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class SasaranSrt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SASARAN_SRT}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'ID_JENISPREMIS'], 'required'],
            [['ID_JENISPREMIS', 'JUMPREMIS', 'PENCAPAIAN1', 'PENCAPAIAN2', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'safe'],
            // [['ID_JENISPREMIS'], 'safe'],
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
            'NOSIRI' => 'No. Siri',
            'ID_JENISPREMIS' => 'Jenis Premis',
            'JUMPREMIS' => 'Jumlah Premis (200 M)',
            'PENCAPAIAN1' => 'Pencapaian (200 M)',
            'PENCAPAIAN2' => 'Pencapaian (>200 M)',
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
    public function getPremis()
    {
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI']);  

    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getJumSasaran()
    {
        $jumlah = Yii::$app->db->createCommand("SELECT PENCAPAIAN1 + PENCAPAIAN2 FROM TBSASARAN_SRT WHERE NOSIRI ='$this->NOSIRI'")->queryScalar();
        $sumjumpremis = Yii::$app->db->createCommand("SELECT sum(JUMPREMIS) FROM TBSASARAN_SRT WHERE NOSIRI ='$this->NOSIRI'")->queryScalar();
        $sumpencapaian1 = Yii::$app->db->createCommand("SELECT sum(PENCAPAIAN1) FROM TBSASARAN_SRT WHERE NOSIRI ='$this->NOSIRI'")->queryScalar();
        $sumpencapaian2 = Yii::$app->db->createCommand("SELECT sum(PENCAPAIAN2) FROM TBSASARAN_SRT WHERE NOSIRI ='$this->NOSIRI'")->queryScalar();
        // $jumpremis = Yii::$app->db->createCommand("SELECT SUM(JUMPREMIS) FROM TBSASARAN_SRT WHERE NOSIRI = '$this->NOSIRI'")->queryScalar();

        $data = [];
        $data['jumlah']=$jumlah;
        $data['sumjumpremis']=$sumjumpremis;
        $data['sumpencapaian1']=$sumpencapaian1;
        $data['sumpencapaian2']= $sumpencapaian2;
        $data['jumpremis']= $jumpremis;

        return $data;

    }

}
