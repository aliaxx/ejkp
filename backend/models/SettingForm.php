<?php

namespace backend\models;

use Yii;

/**
 * SettingForm is the model behind the settings form.
 */
class SettingForm extends \yii\base\Model
{
    public $setting_1;
    public $setting_2;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_1', 'setting_1'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'setting_1' => Yii::t('app', 'Setting 1'),
            'setting_2' => Yii::t('app', 'Setting 2'),
        ];
    }
}
