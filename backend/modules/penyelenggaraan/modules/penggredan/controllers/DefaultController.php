<?php

namespace backend\modules\parameter\modules\param\controllers;

use yii\web\Controller;

/**
 * Default controller for the `ParamHeader` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
