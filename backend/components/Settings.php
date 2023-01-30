<?php

namespace app\components;

use Yii;

use common\models\Setting;

/**
 * Settings Class
 */
class Settings extends \yii\base\Component
{
    public function init()
    {
        parent::init();

        $this->populate();
    }

    public function set($code, $value)
    {
        $model = Setting::findOne($code);
        if (!$model) {
            $model = new Setting();
            $model->code = $code;
        }

        $model->value = $value;
        if ($model->save()) {
            $this->populate();
            return true;
        } else return false;
    }

    public function unset($code)
    {
        $model = Setting::findOne($code);
        if ($model) $model->delete();
    }

    public function get($code, $default = null)
    {
        if (isset(Yii::$app->params['settings'][$code])) return Yii::$app->params['settings'][$code];
        elseif ($default) return $default;
        else return false;
    }

    public function can($code)
    {
        if ($value = $this->get($code)) {
            if (is_numeric($value)) {
                return ($value)?true:false;
            } elseif (is_bool($value)) {
                return $value;
            }
        }

        return false;
    }

    public function populate()
    {
        $settings = Setting::find()->all();
        foreach ($settings as $setting) {
            Yii::$app->params['settings'][$setting->code] = $setting->value;
        }
    }
}
