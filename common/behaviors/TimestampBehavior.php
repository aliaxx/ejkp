<?php

namespace common\behaviors;

class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    public $createdAtAttribute = 'TRKHDAFTAR';
    public $updatedAtAttribute = 'TRKHAKHIR';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        //$this->value = strtotime('+8 hours', time());  //zihan test 20220803
        $this->value = strtotime('+6 hours', time());  //temporary set to suit local time
    }
}
