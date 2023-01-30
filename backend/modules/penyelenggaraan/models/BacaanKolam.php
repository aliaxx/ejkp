<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "{{%PK_BACAANKOLAM}}".
 *
 * @property int $ID ID
 * @property string|null $NAMAPARAM NAMA PARAMETER
 * @property string|null $NILAIPIAWAI NILAI PIAWAI
 * @property string|null $UNIT UNIT
 * @property int|null $STATUS STATUS
 * @property int|null $PGNDAFTAR PENGGUNA DAFTAR
 * @property string|null $TRKHDAFTAR TARIKH DAFTAR
 * @property int|null $PGNAKHIR PENGGUNA AKHIR
 * @property string|null $TRKHAKHIR TARIKH AKHIR
 */
class BacaanKolam extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    
     public static function tableName()
    {
        return '{{%PK_BACAANKOLAM}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAMAPARAM','NILAIPIAWAI'], 'required'],
            [['ID', 'STATUS', 'PGNDAFTAR', 'PGNAKHIR'], 'integer'],
            [['NAMAPARAM'], 'string', 'max' => 50],
            [['NILAIPIAWAI'], 'string', 'max' => 50],
            [['UNIT'], 'string', 'max' => 10],
            [['TRKHDAFTAR', 'TRKHAKHIR'], 'integer'],
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
            'NAMAPARAM' => 'Nama Parameter',
            'NILAIPIAWAI' => 'Nilai Piawai',
            'UNIT' => 'Unit',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
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

    /**
     * @return yii\db\ActiveQuery
     */
    // public function getAirparamkolam()
    // {
    //     return $this->hasOne(Transkolam::className(), ['IDPARAM' => 'ID']); 
    // }

    
}
