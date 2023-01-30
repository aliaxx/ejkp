<?php
namespace backend\modules\penyelenggaraan;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->modules = [
           'majlis' => ['class' => '\backend\modules\penyelenggaraan\modules\majlis\Module'],
           'param' => ['class' => '\backend\modules\penyelenggaraan\modules\param\Module'],
           'penggredan' => ['class' => '\backend\modules\penyelenggaraan\modules\penggredan\Module'],
           'kolam' => ['class' => '\backend\modules\penyelenggaraan\modules\kolam\Module'], //kolam adalah nama folder
           'dun' => ['class' => '\backend\modules\penyelenggaraan\modules\dun\Module'],
           'subunit' => ['class' => '\backend\modules\penyelenggaraan\modules\subunit\Module'],
           'perundangan' => ['class' => '\backend\modules\penyelenggaraan\modules\perundangan\Module'],
           'lokaliti' => ['class' => '\backend\modules\penyelenggaraan\modules\lokaliti\Module']
         ];
    }
}