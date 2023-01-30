<?php

namespace backend\modules\integrasi\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

use backend\components\LogActions;
use backend\modules\integrasi\models\Sppj;
use backend\modules\integrasi\models\SppjSearch;



/**
 * KategoriController implements the CRUD actions for Perkara model.
 */
class SppjController extends \yii\web\Controller
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
                        'allow' => Yii::$app->access->can('sppj-read'),
                        'actions' => ['index', 'view', ],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LesenMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SppjSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel);
            $actionHandler->execute();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // //called action where the PK are not exist.
    // public function createUrl($id)
    // {
    //     if ($this->urlCreator instanceof Closure) {
    //         return call_user_func($this->urlCreator, $model, $action);
    //     } else {
        
    //      if($this->primaryKey)
    //         {
    //             $key = $this->primaryKey;
    //             $params = [$key=>$model->$key];
    //         }else{
    //             $params = $model->getPrimaryKey(true);
    //              $key = key($params);
    //         }
    //         if (count($params) === 1) {
    //             $params = [$key => reset($params)];
    //         }
    //         return Yii::$app->controller->createUrl($action, $params);
    //     }
    // }

    /**
     * Displays a single LesenMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($NOKMP, $KODAKTA, $KODSALAH)
    {
        $model = $this->findModel($NOKMP, $KODAKTA, $KODSALAH);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();
            
            // Refresh model information
            $model = $this->findModel($NOKMP, $KODAKTA, $KODSALAH);
        }


        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the LesenMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return LesenMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sppj::findOne([$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}