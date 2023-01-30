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
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        ActionHandler::setReturnUrl();
        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($searchModel, [
                'export-csv' => function ($model) use ($dataProvider) {
                    $rows = [];
                    $header = [
                        $model->getAttributeLabel('nokp'),
                        $model->getAttributeLabel('nama'),
                        $model->getAttributeLabel('jantina'),
                        $model->getAttributeLabel('emel'),
                        $model->getAttributeLabel('kodcawangan'),
                        $model->getAttributeLabel('peranan'),
                        $model->getAttributeLabel('data_filter'),
                        $model->getAttributeLabel('status'),
                        $model->getAttributeLabel('pgnakhir'),
                        $model->getAttributeLabel('trkhakhir'),
                    ];
                    $rows[] = $header;

                    $dataProvider->setPagination(false);
                    $models = $dataProvider->getModels();
                    foreach ($models as $model) {
                        $rows[] = [
                            $model->nokp, $model->nama, $model->jantina, $model->emel, $model->kodcawangan, 
                            OptionHandler::resolve('peranan', $model->peranan), 
                            OptionHandler::resolve('data_filter', $model->data_filter),
                            OptionHandler::resolve('status', $model->status),
                            $model->updatedByUser->nama, date('Y-m-d H:i:s', $model->trkhakhir),
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
                if (!$model->kodcawangan) $model->kodcawangan = 'NON';

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
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if (Yii::$app->request->post('action')) {
            $actionHandler = new ActionHandler($model);
            $action = $actionHandler->execute();

            // old data for logging update action
            $oldmodel = json_encode($model->getAttributes());

            if ($action && $model->load(Yii::$app->request->post())) {
                if ($model->password) $model->setPassword($model->password);
                //$model->image = \yii\web\UploadedFile::getInstance($model, 'image');
                if (!$model->kodcawangan) $model->kodcawangan = 'NON';

                // update user 'idnegeri' based on selected kodcawangan
                $model->idnegeri = Cawangan::findOne(['kodcawangan' => $model->kodcawangan])->kodnegeri;

                if ($model->save()) {
                    //$model->saveImage();
                    
                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $model, $oldmodel);

                    Yii::$app->session->setFlash('success', 'Rekod telah berjaya disimpan.');
                    $actionHandler->gotoReturnUrl($model);
                }
            }
        } elseif (Yii::$app->request->isAjax && Yii::$app->request->post('actionRemoveFile')) {
            $model->gambar = null;
            $model->save(false);
            return true;
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == OptionHandler::STATUS_TIDAK_AKTIF) {
            $model->status = OptionHandler::STATUS_AKTIF;
        } else {
            $model->status = OptionHandler::STATUS_TIDAK_AKTIF;
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
        $model = User::findOne(Yii::$app->user->id);
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
        $model = User::findOne(Yii::$app->user->id);
        $response[] = json_decode($id, true);
       // print_r($response);
        foreach($response as $key => $value){
           $idstaf = $value['idstaf'];
           $nokp = $value['nokp'];
           $nama = $value['nama'];
           $nama = addslashes($nama);
           $jantina = $value['jantina'];
           $nama_bahagian = $value['nama_bahagian'];
           $nama_gelaran = $value['nama_gelaran'];
           $nama_gelaran_jawatan = $value['nama_gelaran_jawatan'];
           $nama_jawatan = $value['nama_jawatan'];
           $gred = $value['gred'];
           $idgred = $value['idgred'];
           $no_telefon = $value['no_telefon'];
           $no_tel_bimbit = $value['no_tel_bimbit'];
           $emel = $value['emel'];
           $susunan = $value['susunan'];
           $gambar = $value['gambar'];
           $kata_laluan = $value['kata_laluan'];
           $aktif_staf = $value['aktif_staf'];
           $idbahagian = $value['idbahagian'];
           $idgred_kumpulan = $value['idgred_kumpulan'];
           $idskim = $value['idskim'];
           $idtaraf_jawatan = $value['idtaraf_jawatan'];
           $idnegeri_bahagian = $value['idnegeri_bahagian'];
           $alamat = $value['alamat'];
           $alamat = addslashes($alamat);
           $poskod = $value['poskod'];
           $idnegeri = $value['idnegeri'];
           $tarikh_lahir = $value['tarikh_lahir'];
           $bandar = $value['bandar'];
           $seksyen = $value['seksyen'];
           $unit = $value['unit'];
           $status =1;
           $pgndaftar = $model->id;
           $pgnakhir = $pgndaftar;
           $trkhdaftar = strtotime(date('d-m-Y H:i:s'));
           $trkhakhir = $trkhdaftar;
           $getkodcawangan = \Yii::$app->db->createCommand("SELECT kodcawangan FROM tbcawangan WHERE idcawangan = $idbahagian")->queryOne();
           $kodcawangan = $getkodcawangan['kodcawangan'];

  
           }
           try {
            $Add = \Yii::$app->db->createCommand("INSERT INTO tbpengguna (id,nokp,nama,kodcawangan,jantina,nama_bahagian,nama_gelaran,nama_gelaran_jawatan,nama_jawatan,gred,idgred,no_telefon,no_tel_bimbit,emel,susunan,gambar,kata_laluan,aktif_staf,idbahagian,idgred_kumpulan,idskim,idtaraf_jawatan,idnegeri_bahagian,alamat,poskod,idnegeri,tarikh_lahir,bandar,seksyen,unit,status,pgndaftar,trkhdaftar,pgnakhir,trkhakhir) VALUE ('$idstaf','$nokp','$nama','$kodcawangan','$jantina','$nama_bahagian','$nama_gelaran','$nama_gelaran_jawatan','$nama_jawatan','$gred','$idgred','$no_telefon','$no_tel_bimbit','$emel','$susunan','$gambar','$kata_laluan','$aktif_staf','$idbahagian','$idgred_kumpulan','$idskim','$idtaraf_jawatan','$idnegeri_bahagian','$alamat','$poskod','$idnegeri','$tarikh_lahir','$bandar','$seksyen','$unit','$status','$pgndaftar','$trkhdaftar','$pgnakhir','$trkhakhir')");
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
            $model = User::findOne(Yii::$app->user->id);
            $response[] = json_decode($id, true);
           // print_r($response);
            foreach($response as $key => $value){
               $idstaf = $value['idstaf'];
               $nokp = $value['nokp'];
               $nama = $value['nama'];
               $nama = addslashes($nama);
               $jantina = $value['jantina'];
               $nama_bahagian = $value['nama_bahagian'];
               $nama_gelaran = $value['nama_gelaran'];
               $nama_gelaran_jawatan = $value['nama_gelaran_jawatan'];
               $nama_jawatan = $value['nama_jawatan'];
               $gred = $value['gred'];
               $idgred = $value['idgred'];
               $no_telefon = $value['no_telefon'];
               $no_tel_bimbit = $value['no_tel_bimbit'];
               $emel = $value['emel'];
               $susunan = $value['susunan'];
               $gambar = $value['gambar'];
               $kata_laluan = $value['kata_laluan'];
               $aktif_staf = $value['aktif_staf'];
               $idbahagian = $value['idbahagian'];
               $idgred_kumpulan = $value['idgred_kumpulan'];
               $idskim = $value['idskim'];
               $idtaraf_jawatan = $value['idtaraf_jawatan'];
               $idnegeri_bahagian = $value['idnegeri_bahagian'];
               $alamat = $value['alamat'];
               $alamat = addslashes($alamat);
               $poskod = $value['poskod'];
               $idnegeri = $value['idnegeri'];
               $tarikh_lahir = $value['tarikh_lahir'];
               $bandar = $value['bandar'];
               $seksyen = $value['seksyen'];
               $unit = $value['unit'];
               $status =1;
               $pgnakhir = $model->id;
               $trkhakhir = strtotime(date('d-m-Y H:i:s'));
      
               }
               try {
                $update = \Yii::$app->db->createCommand("UPDATE tbpengguna SET nokp = '$nokp',nama = '$nama',jantina = '$jantina',nama_bahagian = '$nama_bahagian',nama_gelaran = '$nama_gelaran',nama_gelaran_jawatan = '$nama_gelaran_jawatan',nama_jawatan = '$nama_jawatan',gred = '$gred',idgred = '$idgred',no_telefon = '$no_telefon',no_tel_bimbit = '$no_tel_bimbit',emel = '$emel',susunan = '$susunan',gambar =  '$gambar',kata_laluan = '$kata_laluan',aktif_staf = '$aktif_staf',idbahagian = '$idbahagian',idgred_kumpulan = '$idgred_kumpulan',idskim = '$idskim',idtaraf_jawatan = '$idtaraf_jawatan',idnegeri_bahagian = '$idnegeri_bahagian',alamat = '$alamat',poskod = '$poskod',idnegeri = '$idnegeri',tarikh_lahir = '$tarikh_lahir',bandar = '$bandar',seksyen = '$seksyen',unit = '$unit',status = '$status',pgnakhir = '$pgnakhir',trkhakhir = '$trkhakhir'  WHERE id=$idstaf");
                $update->execute();
                Yii::$app->getSession()->setFlash('success', 'Pengguna Berjaya Dikemaskini.');
                return $this->redirect(['index']);
                //return $this->render('add', ['response'=>$response]);
                } catch(\Exception $e) {
                    $e->getMessage();
                    Yii::$app->getSession()->setFlash('error', 'Pengguna Gagal Dikemaskini.'.$e->getMessage());
                    return $this->redirect(['index']);
                }
               
            }

       public function actionCari(){
        $model = new Cari();
        $model->setScenario('cari');
        $dataProvider =null;
        $formData = Yii::$app->request->get();
        if($model->load($formData)){ 
           $search = Html::encode($model->nama_pengguna);
           $jenis = $formData['Cari']['jenis_carian'];
           $value = $formData['Cari']['nama_pengguna'];
            function callAPI($method, $url, $data){
                $curl = curl_init();
             
                switch ($method){
                   case "POST":
                      curl_setopt($curl, CURLOPT_POST, 1);
                      if ($data)
                         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                      break;
                   case "PUT":
                      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                      if ($data)
                         curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                      break;
                   default:
                      if ($data)
                         $url = sprintf("%s?%s", $url, http_build_query($data));
                }
             
                // OPTIONS:
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                   'APP_KEY: aim-forceK6dNs3l1@',
                   'Content-Type: application/json',
                ));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
             
                // EXECUTE:
                $result = curl_exec($curl);
                if(!$result){die("Connection Failure");}
                curl_close($curl);
                return $result;
             }
            
             //$get_data = callAPI('GET', 'http://iems.kpdnhep.gov.my/API_IEMS/api/find/'.$jenis.'/'.$value, false);
             $get_data = callAPI('GET', 'http://iems.kpdnhep.gov.my/API_IEMS/api/find/'.$jenis.'/'.$value, false);
             $response = json_decode($get_data, true);
             if($response == "false"){
                 $response = NULL;
                $dataProvider = new ArrayDataProvider([
                    'key'=>'idstaf',
                    'allModels' => $response,
                    'sort' => [
                        'attributes' => ['idstaf', 'nokp', 'nama'],
                    ],
                    'pagination' => [
                    'pageSize' => 10
                    ],
                    
                ]); 
             }else{
                $dataProvider = new ArrayDataProvider([
                    'key'=>'idstaf',
                    'allModels' => $response,
                    'sort' => [
                        'attributes' => ['idstaf', 'nokp', 'nama'],
                    ],
                    'pagination' => [
                    'pageSize' => 10
                    ],
                    
                ]); 
             }
        }
        return $this->render('cari', ['model' => $model, 'dataProvider' => $dataProvider]);
     }


}
