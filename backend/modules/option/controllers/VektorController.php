<?php

namespace backend\modules\option\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use common\utilities\OptionHandler;
use backend\modules\penyelenggaraan\models\ZonAhliMajlis;
use common\models\Pengguna;



/**
 * Parameter controller
 */
class VektorController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'actions' => ['ahli-majlis','pemeriksa'], //declare all the function here..
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

  /**
     * Return Ahli Majlis list
     */
    public function actionAhliMajlis($search = null, $page = 1)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = ZonAhliMajlis::find()->where(['STATUS' => 1])->orderBy(['ID' => SORT_ASC]);

        //searching at dropdown menu..
        if (!is_null($search)) { 
            $query->andWhere(['or', ['like', 'PRGNZON', $search], ['like', 'NAMAAHLIMAJLIS', $search]]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $output['results'][] = ['id' => $result->ID, 'text' => $result->PRGNZON . ' - ' . $result->NAMAAHLIMAJLIS];
        }

        return $output;
    }

    /**
     * Return Pegawai Pemeriksa list from tbpengguna.
     */
    public function actionPemeriksa($search = null, $page = 1)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $perPage = 20;
        $output['results'] = [];

        $query = Pengguna::find()->where(['STATUS' => 1])->orderBy(['ID' => SORT_ASC]);

        if (!is_null($search)) {
            $query->andWhere(['or', ['like', 'NAMA', $search]]);
        }

        $output['total'] = $query->count('*');
        $results = $query->limit($perPage)->offset(($page - 1) * $perPage)->all();
        foreach ($results as $result) {
            $output['results'][] = ['id' => $result->ID, 'text' => $result->NAMA];
        }

        return $output;
    }
}
