<?php

namespace backend\modules\integrasi\models;

use Yii;

class SewaLocationList extends \yii\db\ActiveRecord  
{

    // get database
    public static function getDb() {
        return Yii::$app->db5;
         // second database
    }

    public static function tableName()
    {
        return '{{SW_LOCATIONS}}';
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
            [['CODE'], 'string', 'max' => 10],
            [['NAME', 'ADDRESS_LINE1', 'ADDRESS_LINE2', 'ADDRESS_LINE3', 'ADDRESS_CITY'], 'string', 'max' => 255],
            [['SECTION_ID', 'LOCATION_TYPE_ID', 'BILLING_PROCESS', 'ADDRESS_POSTCODE', 'ADDRESS_STATE_ID', 'FLOOR_PLAN_GALLERY_ID', 'DISPLAY_STATUS', 'CREATED_BY', 'UPDATED_BY', 'DELETED_BY'], 'number'],
            [['HASIL_CODE', 'REF_CODE'], 'string', 'max' => 50],
            [['GOOGLE_EMBED_URL'], 'string', 'max' => 4000],
            [['CREATED_AT', 'UPDATED_AT', 'DELETED_AT'], 'safe'],
            [['V_ID_KAWASAN', 'V_KOD_SEKSYEN'], 'string', 'max' => 51],
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'Id Lokasi',
            'CODE' => 'Kod',
            'NAME'=> 'Nama Pemohon',
            'ADDRESS_LINE1' => 'Alamat Pemohon 1',
            'ADDRESS_LINE2' => 'Alamat Pemohon 2',
            'ADDRESS_LINE3' => 'Alamat Pemohon 3',
            'ADDRESS_CITY' => 'Bandar',
            'ADDRESS_POSTCODE' => 'Poskod',
        ];
    }

    // public function getLokasi()
    // {
    //     return $this->hasOne(\backend\modules\lawatanmain\models\LawatanMain::class, ['IDLOKASI' => 'LOCATION_ID']);
    // }
    
}
