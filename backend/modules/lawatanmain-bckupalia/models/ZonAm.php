<?php

namespace backend\modules\lawatanmain\models;

use Yii;

/**
 * This is the model class for table "ZonAm".
 *
 * @property string $NOMBOR_ZON
 * @property string $NAMA
 * 
 */
class ZonAm extends \yii\db\ActiveRecord  
{

    public $primaryKey = false;
    
    // get database
    public static function getDb() {
        return Yii::$app->db4;
         // second database
    }

    public static function tableName()
    {
        return '{{V_ZON_AM}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['NOMBOR_ZON'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOMBOR_ZON'], 'number', 'max' => 10],
            [['NAMA'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NOMBOR_ZON'=> 'ID Zon',
            'NAMA'=> 'Nama',
        ];
    }

}
