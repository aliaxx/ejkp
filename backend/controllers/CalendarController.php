<?php

namespace backend\controllers;


use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\utilities\ActionHandler;
use common\utilities\DateTimeHelper;

use backend\models\CalendarSearch;


class CalendarController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new CalendarSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}