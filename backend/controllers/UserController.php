<?php

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use backend\models\UserSearch;
use backend\modules\parameter\models\Cawangan;
use backend\components\LogActions;
use common\utilities\ActionHandler;
use common\utilities\OptionHandler;
use common\models\User;
use common\models\Pengguna;
use common\models\Cari;
use Mpdf\Tag\Form;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'allow' => Yii::$app->access->can('pengguna-read'),
                        'actions' => ['index', 'view','add','cari','kemaskini','daftar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => Yii::$app->access->can('pengguna-write'),
                        'actions' => ['update', 'delete', 'add','cari','kemaskini'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['password'],
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
         $SUBUNIT = null;

         switch (Yii::$app->user->identity->DATA_FILTER) {
             case 2:
                 $SUBUNIT = Yii::$app->user->identity->SUBUNIT;
                 break;
         }
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$SUBUNIT);

        //Print
        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('NOKP'),
                        $model->getAttributeLabel('NAMA'),
                        $model->getAttributeLabel('SUBUNIT'),
                        $model->getAttributeLabel('PERANAN'),
                        $model->getAttributeLabel('DATA_FILTER'),
                        $model->getAttributeLabel('STATUS'),
                        $model->getAttributeLabel('PGNAKHIR'),
                      //  $model->getAttributeLabel('TRKHAKHIR'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->NOKP, $model->NAMA, $model->SUBUNIT, 
                            OptionHandler::resolve('PERANAN', $model->PERANAN), 
                            OptionHandler::resolve('DATA_FILTER', $model->DATA_FILTER),
                            OptionHandler::resolve('STATUS', $model->STATUS),
                            //$model->updatedByUser->NAMA, date('Y-m-d H:i:s', $model->TRKHAKHIR),
                        ];
                    }

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename='.Yii::$app->controller->id.'.csv');
                    $fp = fopen('php://output', 'w');
                    foreach ($rows as $row) {
                        fputcsv($fp, $row);
                    }
                    fclose($fp);
                    exit();
                },
                'export-pdf' => function ($model) use ($dataProvider) {
                    $dataProvider->setPagination(false);

                    $mpdf = new \Mpdf\Mpdf();
                    $mpdf->WriteHTML($this->render('_print', ['dataProvider' => $dataProvider]));
                    $mpdf->Output(Yii::$app->controller->id.'.pdf', \Mpdf\Output\Destination::DOWNLOAD);
                },
            ]);
            $actionHandler->execute();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
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
     * NOTE: NOT USED
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->setScenario('create');

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
                //$model->image = \yii\web\UploadedFile::getInstance($model, 'image');
                if (!$model->SUBUNIT) $model->SUBUNIT = 'NON';

                if ($model->save()) {
                    $model->setRoles(['admin']);
                    //$model->saveImage();

                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_CREATE, $model);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);
    //     $model->setScenario('update');

    //     if (Yii::$app->request->post('action')) {
    //         $actionHandler = new ActionHandler($model);
    //         $action = $actionHandler->execute();

    //         // old data for logging update action
    //         $oldmodel = json_encode($model->getAttributes());

    //         if ($action && $model->load(Yii::$app->request->post())) {
    //             if ($model->KATA_LALUAN) $model->setPassword($model->KATA_LALUAN);
    //             //$model->image = \yii\web\UploadedFile::getInstance($model, 'image');
    //          //   if (!$model->SUBUNIT) $model->SUBUNIT = 'NON';

    //             // update user 'idnegeri' based on selected SUBUNIT
    //          //   $model->idnegeri = Cawangan::findOne(['SUBUNIT' => $model->SUBUNIT])->kodnegeri;

    //             if ($model->save()) {
    //                 //$model->saveImage();
                    
    //                 // record log
    //                 $log = new LogActions;
    //                 $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

    //                 Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
    //                 $actionHandler->gotoReturnUrl($model);
    //             }
    //         }
    //     } elseif (Yii::$app->request->isAjax && Yii::$app->request->post('actionRemoveFile')) {
    //         $model->gambar = null;
    //         $model->save(false);
    //         return true;
    //     }

    //     return $this->render('update', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->ID == 1) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {
                if ($model->save()) {
                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->STATUS == OptionHandler::STATUS_TIDAK_AKTIF) {
            $model->STATUS = OptionHandler::STATUS_AKTIF;
        } else {
            $model->STATUS = OptionHandler::STATUS_TIDAK_AKTIF;
        }

        if ($model->save(false)) {
            // record log
            $log = new LogActions;
            $log->recordLog($log::ACTION_DELETE, $model);

            Yii::$app->session->setFlash('success', 'Status rekod telah berjaya ditukar.');

            $actionHandler = new ActionHandler($model);
            $actionHandler->gotoReturnUrl($model);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Change password screen.
     */
    public function actionPassword()
    {
        $model = User::findOne(Yii::$app->user->ID);
        if (!$model) throw new NotFoundHttpException('The requested page does not exist.');

        $model->setScenario('changePassword');

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            if ($action && $model->load(Yii::$app->request->post())) {
                $model->setPassword($model->password);
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('success', 'Kata Laluan baru telah berjaya disimpan.');
                    return $this->refresh();
                }
            }
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    public function actionAdd($id)
    {  

        // var_dump($id);
        // exit();


        $model = User::findOne(Yii::$app->user->id);
        $response[] = json_decode($id, true);
       // print_r($response);
        foreach($response as $key => $value){
            $ID = $value['USERID'];
            $nokp = $value['NIRC'];
            // $kata_laluan = $value['USERPASSWORD'];
            // $kodunit = $value['DEPARTMENTCODE'];
            $nama = $value['NAME'];
            // $data_filter = $value['USERTYPECODE'];
            // $peranan = $value['DESIGNATION'];
            $status =1;
            $pgndaftar = $model->id;
            $pgnakhir = $pgndaftar;
            $trkhdaftar = strtotime(date('d-m-Y H:i:s'));
            $trkhakhir = $trkhdaftar;
            // $authkey = $value['OFFICERID'];
            // $subunit = $value['USERGROUPCODE'];
            $email = $value['EMAIL'];
            $username = $value['USERNAME'];
            $customerid = $value['CUSTOMERID']; 
            // $trkhtukarpassword = $value['USERCHANGEPASSWORDTIMESTAMP'];  
           }
           try {
            $Add = \Yii::$app->db->createCommand("INSERT INTO TBPENGGUNA (ID, NOKP,NAMA, STATUS,PGNDAFTAR, TRKHDAFTAR,PGNAKHIR,TRKHAKHIR, EMAIL, USERNAME,CUSTOMERID)  
            VALUES ('$ID','$nokp','$nama','$status','$pgndaftar','$trkhdaftar','$pgnakhir','$trkhakhir','$email','$username','$customerid')");
           
        //    var_dump($Add);
        //    exit();

        
            $Add->execute();
            Yii::$app->getSession()->setFlash('success', 'Pengguna Berjaya di Tambah.');
            return $this->redirect(['index']);
            //return $this->render('add', ['response'=>$response]);
            } catch(\Exception $e) {
                $e->getMessage();
                Yii::$app->getSession()->setFlash('error', 'Pengguna Gagal di Tambah.'.$e->getMessage());
                return $this->redirect(['index']);
            }
           
        }

        public function actionKemaskini($id)
        {  
           // var_dump('test');
           // exit();
           
           $model = User::findOne(Yii::$app->user->id);
               $response[] = json_decode($id, true);
              // print_r($response);
               foreach($response as $key => $value){
                   $ID = $value['USERID'];
                   $nokp = $value['NIRC'];
                //    $kata_laluan = $value['USERPASSWORD'];
                   // $kodunit = $value['DEPARTMENTCODE'];
                   $nama = $value['NAME'];
                   // $data_filter = $value['USERTYPECODE'];
                   // $peranan = $value['DESIGNATION'];
                   $status =1;
                   $pgndaftar = $model->id;
                   $pgnakhir = $pgndaftar;
                   $trkhdaftar = strtotime(date('d-m-Y H:i:s'));
                   $trkhakhir = $trkhdaftar;
                   // $authkey = $value['OFFICERID'];
                   // $subunit = $value['USERGROUPCODE'];
                   $email = (!empty($model->EMAIL))? $model->EMAIL: null;
                   $username = $value['USERNAME'];
                   $customerid = $value['CUSTOMERID']; 
                   // $trkhtukarpassword = $value['USERCHANGEPASSWORDTIMESTAMP'];
               }
                  try {
                   $update = \Yii::$app->db->createCommand("UPDATE TBPENGGUNA SET NOKP = '$nokp',NAMA = '$nama',email = '$email',
                   CUSTOMERID='$customerid',STATUS = '$status',PGNDAFTAR = '$pgndaftar',TRKHDAFTAR = '$trkhdaftar',
                   PGNAKHIR = '$pgnakhir',TRKHAKHIR = '$trkhakhir' WHERE ID = '$ID'");
                   $update->execute();
                   Yii::$app->getSession()->setFlash('success', 'Pengguna Berjaya Dikemaskini.');
                   return $this->redirect(['index']);
                   //return $this->render('add', ['response'=>$response]);
                   } catch(\Exception $e) {
                       $e->getMessage();
                       Yii::$app->getSession()->setFlash('error', 'Pengguna Gagal Dikemaskini.'.$e->getMessage());
                       return $this->redirect(['index']);
                   }
   
                   // print_r($update->errors);
                   // exit;
           }
      
    public function actionCari(){
        $model = new Cari();
        
        $model->setScenario('cari');
       
        $dataProvider =null;

        $formData = Yii::$app->request->get();
        if($model->load($formData)){
            $search = Html::encode($model->nama_pengguna);
            $jenis = $formData['Cari']['jenis_carian'];
            $value = ($formData['Cari']['nama_pengguna']);
            
            // var_dump($value);
            //  exit();
            $get_data = (new \yii\db\Query())
                ->select('*')
                ->from('C##MAJLIS.PRUSER')
                // ->where(['like','toLowerCase(NAME)', $value])
                ->where(['LIKE', 'LOWER(NAME)', strtolower($value)])//user can search using lowercase or uppercase. Nurul 30122022
                ->orWhere(['NIRC'=>$value])
                ->all();  
            
            $dataProvider = new ArrayDataProvider([
                'key'=>'USERNAME',
                'allModels' => $get_data,
                'sort' => [
                    'attributes' => ['USERNAME', 'NAME'],
                ],
                'pagination' => [
                    'pageSize' => 10
                ],
            ]);
        }
        return $this->render('cari', ['model' => $model, 'dataProvider' => $dataProvider]);
    }


}
