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
class Penjaja extends \yii\db\ActiveRecord  
{
    // add the function below:
    public static function getDb() {
        return Yii::$app->db2;
         // second database
    }

    public static function tableName()
    {
        return '{{V_EJKP_PENJAJA}}';
    }

    //create fake PK where the table don't have any PK.
    public static function primaryKey()
    {
        return ['NO_AKAUN'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID_PERMOHONAN', 'AMAUN_LESEN', 'ID_KAWASAN'], 'number'],
            [['NO_AKAUN'], 'string', 'max' => 26],
            [['JENIS_LESEN'], 'string', 'max' => 13],
            [['LOKASI_MENJAJA'], 'string', 'max' => 56],
            [['JENIS_JUALAN'], 'string', 'max' => 15],
            [['KAWASAN'], 'string', 'max' => 41],
            [['JENIS_JAJAAN'], 'string', 'max' => 11],
            [['NO_AKAUN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NO_AKAUN' => 'No.Akaun',
            'ID_PERMOHONAN' => 'ID Permohonan Lesen',
            'JENIS_LESEN' => 'Jenis Lesen',
            'AMAUN_LESEN' => 'Amaun Lesen',
            'LOKASI_MENJAJA' => 'Lokasi Menjaja',
            'JENIS_JUALAN' => 'Jenis Jualan',
            'KAWASAN' => 'Kawasan',
            'ID_KAWASAN' => 'ID Kawasan',
            'JENIS_JAJAAN' => 'Jenis Jajaan',
        ];
    }

}
