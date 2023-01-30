<?php

namespace backend\modules\makanan\models;

use Yii;
use backend\modules\penyelenggaraan\models\BacaanKolam;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\lawatanmain\models\LawatanMain;

/**
 * This is the model class for table "{{%TRANS_KOLAM}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property string $IDPARAM
 * @property int|null $NILAI1
 * @property int|null $NILAI2
 * @property int|null $NILAI3
 * @property int|null $NILAI4
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Transkolam extends \yii\db\ActiveRecord
{
    public $UNIT,$NILAIPIAWAI;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%TRANS_KOLAM}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI','UNIT','NILAIPIAWAI','NILAI1', 'NILAI2',], 'required'],
            [['IDPARAM','NILAI1', 'NILAI2', 'NILAI3', 'NILAI4', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'safe'],
            [['NOSIRI'], 'string', 'max' => 20],
          //  [['ID', 'NOSIRI'], 'unique', 'targetAttribute' => ['ID', 'NOSIRI']],
            [['ID'], 'unique'],
            [['UNIT','NILAIPIAWAI'], 'safe'],
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
            'IDPARAM' => 'Idparam',
            'NILAI1' => 'Nilai1',
            'NILAI2' => 'Nilai2',
            'NILAI3' => 'Nilai3',
            'NILAI4' => 'Nilai4',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
        ];
    }


    /**
     * @return yii\db\ActiveQuery
     */
    public function getAirkolam()
    {
            // var_dump($this);
            // exit();
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID');  

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBacaankolam()
    {
        return $this->hasOne(\backend\modules\penyelenggaraan\models\BacaanKolam::className(), ['ID' => 'IDPARAM']);
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

    public function getParams(){

        return $this->hasMany(Transkolam::className(), ['NOSIRI' => 'NOSIRI'])->orderBy(['ID' => SORT_ASC]);

    }

    public function getLesen(){

        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);

    }

    public function getMain(){

        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);

    }


    // public function getUnit(){

    //     return $this->hasMany(BacaanKolam::className(), ['ID' => 'IDPARAM']);

    // }

}
