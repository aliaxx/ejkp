<?php

namespace backend\modules\lawatanmain\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;   
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\LogActions;
use yii\web\NotFoundHttpException;

use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use backend\modules\lawatanmain\models\LawatanMain;

/**
 * LawatanMainController implements the CRUD actions for LawatanMain model.
 */
class LawatanMainController extends Controller
{
    /**
     * @inheritDoc
     */
    // public function behaviors()
    // {
    //     return [
    //         'access' => [
    //             'class' => AccessControl::className(),
    //         //     'rules' => [
    //         //         [
    //         //             'allow' => Yii::$app->access->can(),
    //         //             'actions' => ['get-ahlis'],
    //         //             'roles' => ['@'],
    //         //         ],
    //         //         [
    //         //             'allow' => Yii::$app->access->can([$model->IDMODULE'-read']),
    //         //             'actions' => ['file-upload','file-delete'],
    //         //             'roles' => ['@'],
    //         //         ],
    //         //     ],
    //         ],
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }

    //not using 'allow' => Yii::$app->access->can(), sebab controller lawatanmain digunakan untuk kesemua module
    //function tak dapat access kerana tidak declared idmodule
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['get-ahlis', 'file-upload','file-delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    
    /**
     * Finds the LawatanMain model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $NOSIRI Nosiri
     * @return LawatanMain the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LawatanMain::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


     /**
     * Uploading file function form Lawatan Main
     */
    public function actionFileUpload()
    {
        // var_dump('hehehehe');
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isAjax) {

            $model = $this->findModel(Yii::$app->request->post('NOSIRI'));

            if ($model) {
                $option['initialPreview'] = [];
                $option['initialPreviewConfig'] = [];

                $model->files = UploadedFile::getInstances($model, 'files');

                // var_dump('here');
                // exit;
                if ($files = $model->saveAttachment($model->IDMODULE)) {
                    
                    // record log function located at model

                    foreach ($files as $file) {
                        $option['initialPreview'][] = $file;
                        $option['initialPreviewConfig'][] = [
                            'url' => Url::to([
                               '/lawatanmain/lawatan-main/file-delete',
                               'idmodule' => $model->IDMODULE,
                               'nosiri' => $model->NOSIRI,
                               'filename' => basename($file),
                            ]),
                        ];
                    }
                }
                return $option;
            }
        }
        return ['error' => Yii::t('app', 'Fail to upload files.')];
    }

    /**
     * Delete file at form Lawatan Main
     */
    public function actionFileDelete($idmodule, $nosiri, $filename)
    {
        $model = $this->findModel($nosiri);

        if($model){
            $ret = $model->deleteFile($idmodule, $nosiri, $filename);  
        }
        
        return $ret;
    }


    /**
     * Display Pasukan Ahli at form Lawatan Main
     */
    public function actionGetAhlis($NOSIRI = null) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $output = [];
        $ahliRecords = [];

        $model = $this->findModel($NOSIRI);
            // var_dump($model);
            // exit();
        foreach ($model->pasukanAhlis as $ahli) {
            if ($ahli->JENISPENGGUNA == 2) {
                if (!empty(\common\models\Pengguna::findOne(['ID' => $ahli->IDPENGGUNA, 'STATUS' => 1]))) {
                    $ahliRecords[$ahli->IDPENGGUNA] = $ahli->pengguna0->NAMA;
                }
            }
        }

        $output = $ahliRecords;

        return $output;
    }

    /**
     * Print vektor document
     * and display it inline at browser
     */
    // public function actionPrint($id)
    // {
    //     $model = $this->findModel($id);
    //     $mpdf = new Mpdf();
    //     $mpdf->WriteHTML($this->render('_print', ['model' => $model]));
    //     $mpdf->Output($model->nokmp . '.pdf', Destination::INLINE);
    // }


}
