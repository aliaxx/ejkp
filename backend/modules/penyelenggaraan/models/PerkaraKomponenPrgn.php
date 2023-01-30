<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;
use backend\modules\penyelenggaraan\models\PerkaraKomponen;


/**
 * This is the model class for table "%PP_PERKARA_KOMPONEN_PRGN".
 *
 * @property string $KODPERKARA
 * @property string $KODKOMPONEN
 * @property string $KODPRGN
 * @property string|null $PRGN
 * @property string|null $MARKAH
 * @property int $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property int $JENIS
 */
class PerkaraKomponenPrgn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PP_PERKARA_KOMPONEN_PRGN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['JENIS','KODPERKARA', 'KODKOMPONEN', 'KODPRGN'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'JENIS'], 'integer'],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['KODKOMPONEN'], 'string', 'max' => 2],
            [['KODPRGN'], 'string', 'max' => 4],
            [['PRGN'], 'string', 'max' => 150],
            [['MARKAH'], 'string', 'max' => 45],
            [['JENIS', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN'], 'unique', 'targetAttribute' => ['JENIS', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN']],
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
            'KODPRGN' => 'Kod Keterangan',
            'PRGN' => 'Keterangan',
            'MARKAH' => 'Markah',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pendaftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'ID Pengguna Kemaskini Terakhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
        ];
    }

    // /**
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getPerkaraKomponen()
    // {
    //     //return $this->hasOne(PerkaraKomponenKomponen::className(), ['KODPERKARAKomponen' => 'KODPERKARAKomponen']);
    //     return $this->hasOne(PerkaraKomponen::className(), ['JENIS' => 'JENIS', 'KODPERKARA' => 'KODPERKARA', 'KODKOMPONEN' => 'KODKOMPONEN']);
    // }

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
     * @return \yii\db\ActiveQuery
     */
    public function getKodperkara0()
    {
        return $this->hasOne(Perkara::className(), ['KODPERKARA' => 'KODPERKARA']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKodkomponen0()
    {
        return $this->hasOne(PerkaraKomponen::className(), ['KODKOMPONEN' => 'KODKOMPONEN','KODPERKARA' => 'KODPERKARA']);
    }    
}
