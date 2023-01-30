<?php

namespace backend\modules\vektor\models;

use Yii;

/**
 * This is the model class for table "TBPV_PEMERIKSAANTANDAS_MARKAH".
 *
 * @property int $NOSIRI
 * @property string|null $KODPERKARA
 * @property string|null $KODKOMPONEN
 * @property string|null $KODPRGN
 * @property int|null $ML
 * @property int|null $MW
 * @property int|null $MO
 * @property int|null $MU
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class PemeriksaanTandasMarkah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TBPV_PEMERIKSAANTANDAS_MARKAH';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOSIRI'], 'required'],
            [['NOSIRI', 'ML', 'MW', 'MO', 'MU', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['KODKOMPONEN'], 'string', 'max' => 2],
            [['KODPRGN'], 'string', 'max' => 4],
            [['NOSIRI'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NOSIRI' => 'Nosiri',
            'KODPERKARA' => 'Kodperkara',
            'KODKOMPONEN' => 'Kodkomponen',
            'KODPRGN' => 'Kodprgn',
            'ML' => 'Ml',
            'MW' => 'Mw',
            'MO' => 'Mo',
            'MU' => 'Mu',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
        ];
    }
}
