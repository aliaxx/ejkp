<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;
/**
 * This is the model class for table "%PP_PERKARA".
 *
 * @property string $KODPERKARA
 * @property int $JENIS
 * @property string|null $PRGN
 * @property int $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Perkara extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PP_PERKARA}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['KODPERKARA', 'JENIS'], 'required'],
            [['JENIS', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['PRGN'], 'string', 'max' => 100],
            [['KODPERKARA', 'JENIS'], 'unique', 'targetAttribute' => ['KODPERKARA', 'JENIS']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'KODPERKARA' => 'Kod Perkara',
            'JENIS' => 'Jenis',
            'PRGN' => 'Keterangan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pendaftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => '	ID Pengguna Kemaskini Terakhir ',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
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
}
