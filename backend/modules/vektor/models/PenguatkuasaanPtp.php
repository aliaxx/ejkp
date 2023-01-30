<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * This is the model class for table "{{%PENGUATKUASAAN_PTP}}".
 *
 * @property int|null $JENIS
 * @property string|null $NOSIRI
 * @property string|null $NOSAMPEL
 * @property string|null $NOLOT
 * @property string|null $BANGUNAN
 * @property string|null $TAMAN
 * @property string|null $NAMAPESALAH
 * @property int|null $ID_JENISPREMIS
 * @property string|null $TRKHSALAH
 * @property int|null $LIPUTAN
 * @property int|null $ID_JENISPEMBIAKAN
 * @property int|null $ID_TINDAKAN
 * @property int|null $ID_SEBABNOTIS
 * @property int|null $BILBEKASMUSNAH
 * @property string|null $KOORDINASI
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class PenguatkuasaanPtp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PENGUATKUASAAN_PTP}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOSAMPEL', 'JENIS'], 'required'],
            [['JENIS', 'ID_JENISPREMIS', 'LIPUTAN', 'ID_JENISPEMBIAKAN', 'ID_TINDAKAN', 'ID_SEBABNOTIS', 'BILBEKASMUSNAH', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOSAMPEL', 'LATITUDE', 'LONGITUDE'], 'string', 'max' => 20],
            [['NOLOT'], 'string', 'max' => 15],
            [['BANGUNAN', 'TAMAN'], 'string', 'max' => 30],
            [['NAMAPESALAH'], 'string', 'max' => 150],
            [['TRKHSALAH'], 'safe'],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'JENIS' => 'Jenis',
            'NOSIRI' => 'No siri',
            'NOSAMPEL' => 'No Sampel',
            'NOLOT' => 'No Lot',
            'BANGUNAN' => 'Tingkat/Blok/Jalan',
            'TAMAN' => 'Taman/Kampung/Flat',
            'NAMAPESALAH' => 'Nama Pesalah',
            'ID_JENISPREMIS' => 'Jenis Premis',
            'TRKHSALAH' => 'Tarikh Salah',
            'LIPUTAN' => 'Liputan Pemeriksaan',
            'ID_JENISPEMBIAKAN' => 'Jenis Pembiakan',
            'ID_TINDAKAN' => 'Tindakan',
            'ID_SEBABNOTIS' => 'Sebab Kompaun Tidak Diberi',
            'BILBEKASMUSNAH' => 'Bil. Bekas Musnah',
            'LATITUDE' => 'Latitud',
            'LONGITUDE' =>'Longitud',
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

    /**
     * @return yii\db\ActiveQuery
     */
    public function getPremis()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISPREMIS'])
        ->onCondition(['KODJENIS' => '8']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getLiputan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'LIPUTAN'])
        ->onCondition(['KODJENIS' => '25']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getJenisPembiakan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISPEMBIAKAN'])
        ->onCondition(['KODJENIS' => '27']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getTindakan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_TINDAKAN'])
        ->onCondition(['KODJENIS' => '28']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getSebab()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_SEBABNOTIS'])
        ->onCondition(['KODJENIS' => '29']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getBekas()
    {
        return $this->hasMany(PenguatkuasaanBekasPtp::className(), ['NOSIRI' => 'NOSIRI', 'NOSAMPEL' => 'NOSAMPEL']);
    }

}
