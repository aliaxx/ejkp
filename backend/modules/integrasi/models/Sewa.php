<?php

namespace backend\modules\integrasi\models;

use Yii;
// namespace backend\modules\lawatanmain\models\LawatanMain;

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
class Sewa extends \yii\db\ActiveRecord  
{

    // get database
    public static function getDb() {
        return Yii::$app->db5;
         // second database
    }

    public static function tableName()
    {
        return '{{V_SEWA_EJKP}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['ACCOUNT_NUMBER'];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ACCOUNT_NUMBER', 'LICENSE_NUMBER'], 'string', 'max' => 15],
            [['NAME'], 'string', 'max' => 100],
            [['ADDRESS_1','ADDRESS_2','ADDRESS_3','LOCATION_NAME'], 'string', 'max' => 255],
            [['ADDRESS_POSTCODE','LOT_NO','ASSET_ADDRESS_POSTCODE'], 'string', 'max' => 5],
            [['LOCATION_ID'], 'number'],
            [['RENT_CATEGORY','SALES_TYPE'], 'string', 'max' => 255],
            [['ASSET_ADDRESS_1','ASSET_ADDRESS_2','ASSET_ADDRESS_3','LOCATION_NAME'], 'string', 'max' => 255],
            [['ASSET_ADDRESS_LAT','ASSET_ADDRESS_LONG'], 'string', 'max' => 70],
            [['RENT_AMOUNT','OUTSTANDING_RENT_AMOUNT'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ACCOUNT_NUMBER' => 'No.Akaun',
            'LICENSE_NUMBER' => 'ID Permohonan',
            'NAME'=> 'Nama Pemohon',
            'ADDRESS_1' => 'Alamat Pemohon 1',
            'ADDRESS_2' => 'Alamat Pemohon 2',
            'ADDRESS_3' => 'Alamat Pemohon 3',
            'ADDRESS_POSTCODE' => 'Poskod',
            'LOT_NO' => 'No Petak/Gerai',
            'LOCATION_ID' => 'Id Lokasi',
            'LOCATION_NAME' => 'Nama Lokasi',
            'RENT_CATEGORY' => 'Kategori Sewa',
            'SALES_TYPE' => 'Jenis Jualan',
            'ASSET_ADDRESS_1' => 'Alamat Premis 1',
            'ASSET_ADDRESS_2' => 'Alamat Premis 2',
            'ASSET_ADDRESS_3' => 'Alamat Premis 3',
            'ASSET_ADDRESS_POSTCODE' => 'Poskod Premis',
            'ASSET_ADDRESS_LAT' => 'Latitud',
            'ASSET_ADDRESS_LONG' => 'Longitud',
            'RENT_AMOUNT' => 'Sewa Bulanan',
            'OUTSTANDING_RENT_AMOUNT' => 'Kadar Sewa Tertunggak',
        ];
    }

    // public function getLokasi()
    // {
    //     return $this->hasOne(\backend\modules\lawatanmain\models\LawatanMain::class, ['IDLOKASI' => 'LOCATION_ID']);
    // }
    
}
