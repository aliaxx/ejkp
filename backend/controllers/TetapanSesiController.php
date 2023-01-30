<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\utilities\ActionHandler;
use common\models\TetapanSesi;

/**
 * Site controller
 */
class TetapanSesiController extends Controller
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $model = TetapanSesi::findOne(['jenis' => 'timeout']);

        if (empty($model)) {
            $model = new TetapanSesi;
        }

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                $model->jenis = 'timeout';

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Kemaskini tetapan sesi telah berjaya.');
                } else {
                    Yii::$app->session->setFlash('danger', 'Kemaskini tetapan sesi telah gagal.');
                }
            }
        }

        return $this->render('_form', [
            'model' => $model,
        ]);
    }
}
