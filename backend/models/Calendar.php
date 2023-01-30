<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "TBCALENDAR".
 *
 * @property float $ID
 * @property string|null $TARIKH
 * @property string|null $VALUE
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%CALENDAR}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TARIKH', 'VALUE'], 'required'],
            [['ID'], 'number'],
            //[['TARIKH'], 'string', 'max' => 7],
            [['TARIKH'], 'safe'],
            [['VALUE'], 'string', 'max' => 20],
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
            'TARIKH' => 'Tarikh',
            'VALUE' => 'Value',
        ];
    }
}
