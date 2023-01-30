<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\LoginForm;
use common\utilities\ActionHandler;

use backend\modules\lawatanmain\models\LawatanMain;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['index', 'login', 'error', 'map'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'dashboard'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site/dashboard']);
        } else {
            return $this->redirect(['/site/login']);
        }
    }

    public function actionDashboard()
    {
        return $this->render('dashboard');
       
    }

    public function actionLogin()
    {
        //Yii::$app->user->login(\common\models\PrUser::findOne(['USERID' => 1]));
        $this->layout = 'guest';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


    /**
     * Display map at dashboard based on selected month 
     */
    // public function actionMap()
    // {
    //     $model= new LawatanMain;

    //     ActionHandler::setReturnUrl();
       
    //     if (Yii::$app->request->post('action')) {
    //         $dataProvider = $model->search(Yii::$app->request->post());

                
    //             $actionHandler->execute();
    //         }

    //     return $this->render('backend/views/site/charts/map', [
    //         'model' => $model,
    //     ]);
    // }
    protected function findModel($id)
    {
        if (($model = LawatanMain::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionMap()
    {
        $model = new LawatanMain;

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $actionHandler->execute();
        
            // var_dump($model);
            // exit;
        }

        return $this->render('charts/_map', [
            'model' => $model,
            'TRKHMULA' => $model->TRKHMULA
        ]);
    }
}
