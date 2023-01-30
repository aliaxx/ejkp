<?php
namespace common\utilities;

use Yii;

class ActionHandler extends \codetitan\handlers\ActionHandler
{
    public function populate()
    {
        parent::populate();

        $action['block'] = function () {
            $model = get_class($this->model);
            $count = $model::updateAll(['is_disabled' => 1], ['ID' => $this->selections, 'is_deleted' => 0]);
            if ($count) $this->setFlash('success', static::t('{count} item blocked', ['count' => $count]).'.');
        };

        $action['unblock'] = function () {
            $model = get_class($this->model);
            $count = $model::updateAll(['is_disabled' => 0], ['ID' => $this->selections, 'is_deleted' => 0]);
            if ($count) $this->setFlash('success', static::t('{count} item unblocked', ['count' => $count]).'.');
        };

        $action['disable'] = function () {
            $model = get_class($this->model);
            $count = $model::updateAll(['is_disabled' => 1], ['ID' => $this->selections, 'is_deleted' => 0]);
            if ($count) $this->setFlash('success', static::t('{count} item disabled', ['count' => $count]).'.');
        };

        $action['enable'] = function () {
            $model = get_class($this->model);
            $count = $model::updateAll(['is_disabled' => 0], ['ID' => $this->selections, 'is_deleted' => 0]);
            if ($count) $this->setFlash('success', static::t('{count} item enabled', ['count' => $count]).'.');
        };

        $this->actions = array_merge($this->actions, $action);
    }
}