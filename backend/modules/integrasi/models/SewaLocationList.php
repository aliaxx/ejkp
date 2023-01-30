<?php

namespace backend\modules\integrasi\models;

use Yii;

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
class SewaLocationList extends \yii\db\ActiveRecord  
{

    public static function tableName()
    {
        return '{{V_LOCATION_LIST}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['LOCATION_ID'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['LOCATION_ID'], 'number'],
            [['LOCATION_NAME'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'LOCATION_ID' => 'ID',
            'LOCATION_NAME' => 'Nama Lokasi',
        ];
    }
    
}
