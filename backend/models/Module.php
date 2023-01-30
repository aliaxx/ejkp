<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "TBMODULE".
 *
 * @property string $ID
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
            [['ID'], 'required'],
            [['ID'], 'string', 'max' => 3],
            [['PRGN'], 'string', 'max' => 50],
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
            'PRGN' => 'Prgn',
        ];
    }
}
