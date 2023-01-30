<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%setting}}".
 *
 * @property string $code
 * @property string $value
 * @property int $updated_at
 * @property int $UPDATED_BY
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SETTING}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['UPDATED_AT'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['UPDATED_AT'],
                ],
            ],
            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['UPDATED_BY'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['UPDATED_BY'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['CODE', 'VALUE'], 'required'],
            [['UPDATED_AT', 'UPDATED_BY'], 'integer'],
            [['CODE', 'VALUE'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CODE' => Yii::t('app', 'Code'),
            'VALUE' => Yii::t('app', 'Value'),
            'UPDATED_AT' => Yii::t('app', 'Updated At'),
            'UPDATED_BY' => Yii::t('app', 'Updated By'),
        ];
    }
}
