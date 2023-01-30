<?php

namespace common\behaviors;

class BlameableBehavior extends \yii\behaviors\BlameableBehavior
{
    public $createdByAttribute = 'PGNDAFTAR';
    public $updatedByAttribute = 'PGNAKHIR';
}
