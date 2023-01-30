<?php

namespace common\utilities;

use Yii;

class MenuHelper
{
    const TYPE_ACTION = 'action';
    const TYPE_CONTROLLER = 'controller';
    const TYPE_MODULE = 'module';
    const TYPE_ROUTE = 'route';

    public static function isActive($rules, $type = 'route')
    {
        if (!is_array($rules)) $rules = (array) $rules;

        if ($type == self::TYPE_ACTION) return in_array(Yii::$app->controller->action->id, $rules);
        elseif ($type == self::TYPE_CONTROLLER) return in_array(Yii::$app->controller->id, $rules);
        elseif ($type == self::TYPE_MODULE) return in_array(Yii::$app->controller->module->id, $rules);
        elseif ($type == self::TYPE_ROUTE) {
            $route = Yii::$app->controller->route;
            if (Yii::$app->controller->module->id != Yii::$app->id) {
                $route = dirname($route);
            }

            return in_array($route, $rules);
        }

        return false;
    }
}
