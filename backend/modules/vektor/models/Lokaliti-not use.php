<?php

namespace backend\modules\vektor\models;

use Yii;

/**
 * This is the model class for table "{{%LOKALITI}}".
 *
 * @property float $ID
 * @property int|null $IDMUKIM
 * @property string|null $PRGN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property int|null $IDZONAM
 */
class Lokaliti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%LOKALITI}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['IDMUKIM', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'IDZONAM'], 'integer'],
            [['PRGN'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IDMUKIM' => 'Idmukim',
            'PRGN' => 'Prgn',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
            'IDZONAM' => 'Idzonam',
        ];
    }
}
