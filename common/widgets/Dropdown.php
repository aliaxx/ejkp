<?php

namespace common\widgets;

use \yii\helpers\Html;

class Dropdown extends \yii\bootstrap\Dropdown
{
    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, ['widget' => 'dropdown-menu']);
        $this->options['class']['widget'] .= ' dropdown-menu-right';
    }
}
