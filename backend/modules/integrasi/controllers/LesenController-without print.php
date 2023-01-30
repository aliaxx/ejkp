<?php

namespace backend\modules\penyelenggaraan\modules\integrasi\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;

use backend\components\LogActions;
use backend\modules\penyelenggaraan\models\Penjaja;
use backend\modules\penyelenggaraan\models\LesenMaster;
use backend\modules\penyelenggaraan\modules\integrasi\models\LesenSearch;
use yii\web\Response;

/**
 * KategoriController implements the CRUD actions for Perkara model.
 */
class LesenController extends Controller
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
                        'allow' => Yii::$app->access->can('lesen-read'),
                        'actions' => ['index', 'view', ],
                        'roles' => ['@'],
                    ],
                    // [
                    //     'allow' => Yii::$app->access->can('perkara-write'),
                    //     'actions' => ['create', 'update', 'delete'],
                    //     'roles' => ['@'],
                    // ],
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
        $searchModel = new LesenSearch();
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

    //called action where the PK are not exist.
    
    public function createUrl($model, $action)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $model, $action);
        } else {
        
         if($this->primaryKey)
            {
                $key = $this->primaryKey;
                $params = [$key=>$model->$key];
            }else{
                $params = $model->getPrimaryKey(true);
                 $key = key($params);
            }
            if (count($params) === 1) {
                $params = [$key => reset($params)];
            }
            return Yii::$app->controller->createUrl($action, $params);
        }
    }

    /**
     * Displays a single LesenMaster model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();
            
            // Refresh model information
            $model = $this->findModel($id);
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
        if (($model = LesenMaster::findOne([$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
