<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "%PP_PERKARA_KOMPONEN".
 *
 * @property string $KODPERKARA
 * @property string $KODKOMPONEN
 * @property string|null $PRGN
 * @property int $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property int $JENIS
 */
class PerkaraKomponen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PP_PERKARA_KOMPONEN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['KODPERKARA', 'KODKOMPONEN', 'JENIS'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'JENIS'], 'integer'],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['KODKOMPONEN'], 'string', 'max' => 2],
            [['PRGN'], 'string', 'max' => 150],
            [['KODPERKARA', 'KODKOMPONEN', 'JENIS'], 'unique', 'targetAttribute' => ['KODPERKARA', 'KODKOMPONEN', 'JENIS']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'JENIS' => 'Jenis',
            'KODPERKARA' => 'Kod Perkara',
            'KODKOMPONEN' => 'Kod Komponen',
            'PRGN' => 'Keterangan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pendaftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'ID Pengguna Kemaskini Terakhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',            
        ];
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodperkara0()
    {
        return $this->hasOne(Perkara::className(), ['KODPERKARA' => 'KODPERKARA']);
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

}
