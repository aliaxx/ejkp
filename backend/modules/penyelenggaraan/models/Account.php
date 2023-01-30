<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "TBACCOUNT".
 *
 * @property float $ID
 * @property string $PARENT
 * @property string $NAME
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ACCOUNT}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'PARENT', 'NAME'], 'required'],
            [['ID'], 'number'],
            [['PARENT', 'NAME'], 'string', 'max' => 20],
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
            'PARENT' => 'Parent',
            'NAME' => 'Name',
        ];
    }
}
