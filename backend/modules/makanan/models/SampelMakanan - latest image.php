<?php

namespace backend\modules\makanan\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;

/**
 * This is the model class for table "{{%SAMPEL_SM}}".
 *
 * @property float $ID
 * @property string|null $NOSIRI
 * @property string|null $NOSAMPEL
 * @property string|null $TRKHSAMPEL
 * @property string|null $JENIS_SAMPEL
 * @property int|null $ID_JENISANALISIS1 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 19
 * @property int|null $ID_JENISANALISIS2 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 19
 * @property int|null $ID_JENISANALISIS3 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 19
 * @property string|null $JENAMA
 * @property float|null $HARGA
 * @property string|null $PEMBEKAL
 * @property string|null $CATATAN
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class SampelMakanan extends \yii\db\ActiveRecord
{
    const IMAGE_PATH = 'images/sampelmakanan';
    public $files;
    public $namagambar;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SAMPEL_SM}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'NOSAMPEL', 'JENIS_SAMPEL' , 'TRKHSAMPEL', 'ID_JENISANALISIS1', 'HARGA'], 'required'],
            [['ID_JENISANALISIS1', 'ID_JENISANALISIS2', 'ID_JENISANALISIS3', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOSAMPEL'], 'string', 'max' => 20],
            [['TRKHSAMPEL', 'HARGA', 'namagambar' , 'files'], 'safe'],
            [['JENIS_SAMPEL', 'JENAMA', 'PEMBEKAL', 'CATATAN'], 'string', 'max' => 255],
            // [['image'], 'file', 'extensions'=>'jpg, gif, png'], //cause button turn to green -NOR23092022
            [['ID'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NOSIRI' => 'No Siri',
            'NOSAMPEL' => 'No Rujukan Sampel',
            'TRKHSAMPEL' => 'Tarikh & Masa Persampelan',
            'JENIS_SAMPEL' => 'Jenis Sampel/ Makanan',
            'ID_JENISANALISIS1' => 'Jenis Analisis 1',
            'ID_JENISANALISIS2' => 'Jenis Analisis 2',
            'ID_JENISANALISIS3' => 'Jenis Analisis 3',
            'JENAMA' => 'Jenama Makanan',
            'HARGA' => 'Harga Sampel(RM)',
            'PEMBEKAL' => 'Alamat Pengimport/ Pengilang/ Pembungkus/ Pembekal',
            'CATATAN' => 'Catatan Keputusan',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini',
        ];
    }

    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
    }

    public function getCreatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNDAFTAR']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedByUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PGNAKHIR']);
    }

    public function setFileName() //hold for a while
    {
        $query = SampelMakanan::find()->where(['NOSIRI' => $this->NOSIRI])->orderBy(['ID' => SORT_DESC])->one();

        if (isset($query)) {//checks whether a variable is set, returns true if the variable exists and is not NULL, otherwise it returns false. -nor010922
            $siri = $query->ID ;
            // $siri = $query->filename ; //4 stand for  bilangan of (Prefix)(Current year). PT22
            // $siri = substr($query->ID, 4);
            $siriid = (int)$siri + 1; //running no for nosiri will keep +1 for new record
            $siriid = sprintf("%02s", $siriid);
        } else {
            $siriid = '01';
        }

        // if ($this->files) {

        // foreach ($this->files as $file) {
        //     $files = $file->extension;
        // }

        // }
        // var_dump($this->files);
        // exit();

        $id = $this->NOSIRI . '_' . $siriid;

        // var_dump($siri);
        // var_dump("<br>");
        // var_dump($siriid);
        // var_dump("<br>");



        $this->filename = $id; //output idsampel eg: (B)01 -NOR120922
        // var_dump($id);
        // var_dump("<br>");
        // var_dump($this->filename);
        // exit();

    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnalisis1()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISANALISIS1'])
        ->onCondition(['KODJENIS' => '19']);    
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnalisis2()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISANALISIS2'])
        ->onCondition(['KODJENIS' => '19']);    
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAnalisis3()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_JENISANALISIS3'])
        ->onCondition(['KODJENIS' => '19']);    
    }

    /**
     * Get lists of file from folder 
     * @return array
     */
    // to display image in view - amend by NOR18102022
    public function getAttachments()
    {
        $data = [];
        $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH); 
        $path['web'] = Yii::getAlias('@web/' . self::IMAGE_PATH);

        $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));

        $models = SampelMakanan::findOne(['NOSIRI' => $this->NOSIRI, 'ID' => $this->ID]); 
        $id =  $models->ID;

        // $filesToSearch = $this->NOSIRI .'*';    
        $filesToSearch = $this->NOSIRI . '_' . $id .'*'; //will search and return image base on nosiri_id -NOR181022
        // $filesToSearch = 'SMM220000512_20221018090930.png';
        // var_dump($filesToSearch); //SMM22000058_20221018094914*
        // exit();
        $files = FileHelper::findFiles($path['folder'], ['only' => [$filesToSearch]]);

        $i = 0;
        foreach ($files as $file) {
            $data[] = $path['web'] . '/' . basename($files[$i]); //to display only 
            $i = $i + 1;
        }
        return $data;
    }

        /**
     * save image to folder 
     * @return array
     */
    public function saveAttachment() //amend by NOR18102022
    {
        if ($this->files) {
            $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));

            $models = SampelMakanan::findOne(['NOSIRI' => $this->NOSIRI,'ID' => $this->ID]); //get current id of nosiri
            // $models = Yii::$app->db->createCommand("SELECT ID FROM TBSAMPEL_SM WHERE NOSIRI='$this->NOSIRI'")->queryAll();
            // $models = SampelMakanan::find()->where(['NOSIRI' => $this->NOSIRI, 'ID' => $this->ID])->all();
            
            if(($this->ID == null)){
                //if id is null, count and assign to id
                var_dump('test1');
                exit();

                $count = SampelMakanan::find()->count();
                $this->ID = ($count + 1);   
                $id = $this->ID;
            }else{
                //if id is not null, assign and use current id
                var_dump('test2');
                exit();

                $id = $this->ID;
            }

            var_dump($this->NOSIRI);
            exit();


            // $models->ID == null;
            // if($models->ID == null){
            // if(!$models->isNewRecord){
            //     var_dump('test2');
            //     exit();
            //     $id =  $models->ID;

            //     // var_dump($id);
            //     // exit();

            // }else{
            //     var_dump('test1');
            //     exit();
            //     $count = SampelMakanan::find()->count();
            //     $models->ID = ($count + 1);   
            //     $id =  $models->ID;
            //     // var_dump($id);
            //     // exit();


            // }

            $data = [];
            $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH);
            $path['web'] = Yii::getAlias('@web/' . self::IMAGE_PATH);

            foreach ($this->files as $file) {
                $filename = $this->NOSIRI . '_' . $id . '_' . $today . '.' . $file->extension; //eg:SMM2200003_1_20221018173342.jpg
                // $filename = $this->NOSIRI . '_'.$file->basename . $today . '.' . $file->extension;

                $path['file'] = $path['folder'] . '/' . $filename;
                if ($file->saveAs($path['file'])) {
                    $data[] = $path['web'] . '/' . $filename;
                }
                
                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_UPLOAD, $model);
            }
        }
        return $data;
    }

    public function deleteFile($nosiri, $filename)
    {
        $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH);
        $data = $path['folder'] . '/' . $filename;

        // Use unlink() function to delete a file
        if (!@unlink($data)) {
            // echo ("$data cannot be deleted due to an error");
            return false;
        }else { 
            return true;
        }
    }





}
