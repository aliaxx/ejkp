<?php

namespace backend\modules\premis\controllers;

use backend\modules\premis\models\Anugerah;
use backend\modules\premis\models\AnugerahSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AnugerahController implements the CRUD actions for Anugerah model.
 */
class AnugerahController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Anugerah models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AnugerahSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Anugerah model.
     * @param int $NOLESEN Nolesen
     * @param string $NOSYARIKAT Nosyarikat
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($NOLESEN, $NOSYARIKAT)
    {
        return $this->render('view', [
            'model' => $this->findModel($NOLESEN, $NOSYARIKAT),
        ]);
    }

    /**
     * Creates a new Anugerah model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Anugerah();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'NOLESEN' => $model->NOLESEN, 'NOSYARIKAT' => $model->NOSYARIKAT]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCarian()
    {
        $searchModel = new AnugerahSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('carian', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Anugerah model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $NOLESEN Nolesen
     * @param string $NOSYARIKAT Nosyarikat
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($NOLESEN, $NOSYARIKAT)
    {
        $model = $this->findModel($NOLESEN, $NOSYARIKAT);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'NOLESEN' => $model->NOLESEN, 'NOSYARIKAT' => $model->NOSYARIKAT]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Anugerah model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $NOLESEN Nolesen
     * @param string $NOSYARIKAT Nosyarikat
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($NOLESEN, $NOSYARIKAT)
    {
        $this->findModel($NOLESEN, $NOSYARIKAT)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Anugerah model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $NOLESEN Nolesen
     * @param string $NOSYARIKAT Nosyarikat
     * @return Anugerah the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($NOLESEN, $NOSYARIKAT)
    {
        if (($model = Anugerah::findOne(['NOLESEN' => $NOLESEN, 'NOSYARIKAT' => $NOSYARIKAT])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLesen()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $source = LawatanMain::find()->select(['NOSIRI', 'NOLESEN','STATUS']);

            $source = $source->orderBy(['NAMA' => SORT_ASC])->all();
            if ($source) {
                $data = ArrayHelper::map($source, 'NOSIRI', 'NAMA');
                return [
                    'success' => true,
                    'results' => $data,
                ];
            }
            return ['success' => false];
        }
        return null;
    }

}
