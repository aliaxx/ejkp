<?php

namespace backend\modules\integrasi\models;

use Yii;
use backend\modules\vektor\models\PenguatkuasaanPtp;

/**
 * This is the model class for table "TBZON_AHLIMAJLIS".
 *
 * @property string $PRGNPANJANGZON
 * @property string $NAMAAHLIMAJLIS
 * @property string $PENGGAL
 * @property string $PRGNPANJANG
 * @property float $STATUS
 * @property float $ID
 * @property int $PGNDAFTAR
 * @property int $TRKHDAFTAR
 * @property int $PGNAKHIR
 * @property int $TRKHAKHIR
 */
class Sppj extends \yii\db\ActiveRecord  
{

    // get database
    public static function getDb() {
        return Yii::$app->db6;
         // second database
    }

    public static function tableName()
    {
        return '{{VSSP_KOMPAUN}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['NOKMP', 'KODAKTA', 'KODSALAH'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOICMILIK','NODAFTAR'], 'safe'],
            [['NOKMP', 'KODAKTA','KODSALAH'], 'safe'],
            [['TRKHKMP','TRFKMP','TRKHBAYAR','KAUNTER'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NOKMP' => 'No. Kompaun',
            'NOICMILIK' => 'No. IC Pemilik',
            'NODAFTAR'=> 'No. Daftar',
            'TRKHKMP' => 'Tarikh Kompaun',
            'TRFKMP' => 'Taraf Kompaun',
            'KODAKTA' => 'Kod Akta',
            'KODSALAH' => 'Kod Salah',
            'TRKHBAYAR' => 'Tarikh Bayar',
            'KAUNTER' => 'Kaunter',
        ];
    }    
    
    public function getLawatan()
    {
        return $this->hasOne(PenguatkuasaanPtp::className(), ['NOSAMPEL' => 'NOKMP']);
    }

}
