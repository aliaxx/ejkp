<?php

namespace backend\modules\vektor\models;

use Yii;

/**
 * This is the model class for table "TBMODULE".
 *
 * @property string|null $PREFIX
 * @property string|null $PRGN
 */
class Module extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TBMODULE';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PREFIX'], 'string', 'max' => 2],
            [['PRGN'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'PREFIX' => 'Prefix',
            'PRGN' => 'Keterangan',
        ];
    }
}
