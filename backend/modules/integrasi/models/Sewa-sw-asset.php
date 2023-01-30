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
        return '{{SW_ASSETS}}';
    }

    //declare fake primary key
    // public static function primaryKey()
    // {
    //     return ['ACCOUNT_NUMBER'];
    // }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'LOCATION_ID', 'RENT_CATEGORY_ID', 'SALES_TYPE_ID', 'DEPT_ID', 'QUOTA_ID', 'DISPLAY_STATUS', 'CREATED_BY', 'UPDATED_BY', 'DELETED_BY'], 'number'],
            [['RENT_PRICE', 'V_HARGA_PAPAN_IKLAN', 'RENT_STATUS'], 'safe'],
            // [['RENT_STATUS'], 'safe'],
            [['LOT_NO'], 'string', 'max' => 15],
            [['START_OPERATION_TIME', 'END_OPERATION_TIME'], 'string', 'max' => 5],
            [['ADDRESS_LINE1','ADDRESS_LINE2','ADDRESS_LINE3','ADDRESS_CITY', 'OLD_REF_NO1', 'OLD_REF_NO2'], 'string', 'max' => 255],
            [['CREATED_AT', 'UPDATED_AT', 'DELETED_AT'], 'safe'],
            [['V_ID_ASET', 'V_KOD_SEKSYEN'], 'string', 'max' => 51],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'ACCOUNT_NUMBER' => 'No.Akaun',
            // 'LICENSE_NUMBER' => 'ID Permohonan',
            // 'NAME'=> 'Nama Pemohon',
            // 'ADDRESS_1' => 'Alamat Pemohon 1',
            // 'ADDRESS_2' => 'Alamat Pemohon 2',
            // 'ADDRESS_3' => 'Alamat Pemohon 3',
            // 'ADDRESS_POSTCODE' => 'Poskod',
            'LOT_NO' => 'No Petak/Gerai',
            'LOCATION_ID' => 'Id Lokasi',
            // 'LOCATION_NAME' => 'Nama Lokasi',
            // 'RENT_CATEGORY' => 'Kategori Sewa',
            // 'SALES_TYPE' => 'Jenis Jualan',
            // 'ASSET_ADDRESS_1' => 'Alamat Premis 1',
            // 'ASSET_ADDRESS_2' => 'Alamat Premis 2',
            // 'ASSET_ADDRESS_3' => 'Alamat Premis 3',
            // 'ASSET_ADDRESS_POSTCODE' => 'Poskod Premis',
            // 'ASSET_ADDRESS_LAT' => 'Latitud',
            // 'ASSET_ADDRESS_LONG' => 'Longitud',
            // 'RENT_AMOUNT' => 'Sewa Bulanan',
            // 'OUTSTANDING_RENT_AMOUNT' => 'Kadar Sewa Tertunggak',
        ];
    }

    // public function getLokasi()
    // {
    //     return $this->hasOne(\backend\modules\lawatanmain\models\LawatanMain::class, ['IDLOKASI' => 'LOCATION_ID']);
    // }
    
}
