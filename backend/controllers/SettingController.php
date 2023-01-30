<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\models\Setting;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['setting-update'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new \backend\models\SettingForm();

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler(new Setting());
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                foreach (get_object_vars($model) as $code => $value) {
                    if ($model->validate([$code])) {
                        if ($model->{$code} && ($model->{$code} != Yii::$app->settings->get($code))) {
                            Yii::$app->settings->set($code, $value);
                        } elseif (!$model->{$code}) {
                            Yii::$app->settings->unset($code);
                        }
                    }
                }

                return $this->refresh();
            }
        } else {
            if (isset(Yii::$app->params['settings'])) {
                foreach (Yii::$app->params['settings'] as $code => $value) {
                    if (property_exists($model, $code)) $model->{$code} = $value;
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
}
