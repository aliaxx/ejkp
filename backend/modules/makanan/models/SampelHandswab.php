<?php

namespace backend\modules\makanan\models;

use Yii;
use backend\modules\makanan\models\Handswab;
use backend\modules\makanan\models\HandswabSearch;
use backend\modules\makanan\models\SampelHandswabSearch;
use backend\modules\makanan\models\SampelHandswab;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\lawatanmain\models\LawatanMain;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;

/**
 * This is the model class for table "{{%SAMPEL_HS}}".
 *
 * @property float $ID ID
 * @property string|null $NOSIRI NO SIRI
 * @property string|null $IDSAMPEL ID SAMPEL
 * @property string|null $NAMAPEKERJA NAMA PEKERJA
 * @property int|null $NOKP NO K/P@PASSPORT
 * @property string|null $JENIS JENIS
 * @property int|null $TY2 TY2
 * @property int|null $FHC FHC
 * @property int|null $KEPUTUSAN KEPUTUSAN
 * @property string|null $CATATAN CATATAN
 * @property int|null $PGNDAFTAR PENGGUNA DAFTAR
 * @property int|null $TRKHDAFTAR TARIKH DAFTAR
 * @property int|null $PGNAKHIR PENGGUNA KEMASKINI TERAKHIR
 * @property int|null $TRKHAKHIR TARIKH KEMASKINI TERAKHIR
 */
class SampelHandswab extends \yii\db\ActiveRecord
{
    const IMAGE_PATH = 'images/HSW';
    public $files;
    public $namagambar;


    // public $NOKP;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SAMPEL_HS}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['IDSAMPEL', 'NAMAPEKERJA', 'JENIS'], 'required'],
            // [['NOKP', 'NAMAPEKERJA', 'JENIS','TY2', 'FHC'], 'required'],
            [['TY2', 'FHC', 'KEPUTUSAN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'IDSAMPEL', 'NAMAPEKERJA', 'JENIS', 'CATATAN', 'NOKP', 'PERALATAN'], 'safe'],
            // [['NOSIRI', 'IDSAMPEL', 'NOKP'], 'string', 'max' => 20],
            // [['NAMAPEKERJA'], 'string', 'max' => 100],
            // [['JENIS'], 'string', 'max' => 1],
            // [['CATATAN'], 'string', 'max' => 250],
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
            'IDSAMPEL' => 'ID Sampel',
            'NAMAPEKERJA' => 'Nama',
            'NOKP' => 'No. KP/Passport',
            'JENIS' => 'Jenis',
            'TY2' => 'TY2',
            'FHC' => 'FHC',
            'KEPUTUSAN' => 'Keputusan',
            'PERALATAN' => 'Peralatan',
            'CATATAN' => 'Catatan',
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

    /**
     * Set for No Siri according to the format => Contoh : PT2200001 [(Prefix)(Current year)(id)]
     * This function will be called at Controller
     * Current year akan depends on Tarikh Mula Lawatan.
     */

    public function setIdSampel()
    {
        //find idsampel based on nosiri
        $query = SampelHandswab::find()->where(['NOSIRI' => $this->NOSIRI])->orderBy(['IDSAMPEL' => SORT_DESC])->one();

        $subunit= Yii::$app->db->createCommand("SELECT B.PRGN
        FROM TBLAWATAN_MAIN A, TBSUBUNIT B
        WHERE A.IDSUBUNIT = B.ID")->queryOne();

        $unit = '(' . implode('', $subunit) . ')'; //convert array to string -NOR120922

        if (isset($query)) {//checks whether a variable is set, returns true if the variable exists and is not NULL, otherwise it returns false. -nor010922
            $siri = substr($query->IDSAMPEL, 4); //4 stand for  bilangan of (Prefix)(Current year). PT22
            $siriid = (int)$siri + 1; //running no for nosiri will keep +1 for new record
            $siriid = sprintf("%02s", $siriid);
        } else {
            $siriid = '01';
        }

        $idsampel = $unit . $siriid;

        // var_dump($idsampel);
        // exit();

        $this->IDSAMPEL = $idsampel; //output idsampel eg: (B)01 -NOR120922
    }

    /**
     * Get lists of file from folder 
     * @return array
     */
    public function getAttachments()
    {
        $data = [];
        $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH); 
        $path['web'] = Yii::getAlias('@web/' . self::IMAGE_PATH);

        $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));

        $models = SampelHandswab::findOne(['NOSIRI' => $this->NOSIRI, 'ID' => $this->ID]); 
        $id =  $models->ID;

        // $filesToSearch = $this->NOSIRI .'*';    
        $filesToSearch = $this->NOSIRI . '_' . $id .'*'; //will search and return image base on nosiri_id 
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
    public function saveAttachment()
    {
        if ($this->files) {
            $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));

            $models = SampelHandswab::findOne(['NOSIRI' => $this->NOSIRI,'ID' => $this->ID]); //get current id of nosiri
            
            if(($this->ID == null)){
                $count = SampelHandswab::find()->count();
                $this->ID = ($count + 1);   
                $id = $this->ID;
            }else{
                $id = $this->ID;
            }

            $data = [];
            $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH);
            $path['web'] = Yii::getAlias('@web/' . self::IMAGE_PATH);

            foreach ($this->files as $file) {

                $filename = $this->NOSIRI . '_' . $id . '_' . $today . '.' . $file->extension; //eg:SMM2200003_1_20221018173342.jpg

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

    public function getPekerja(){

        return $this->hasMany(SampelHandswab::className(), ['NOSIRI' => 'NOSIRI'])->orderBy(['ID' => SORT_ASC]);

    }

    public function getLesen(){

        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);

    }

    public function getMain(){

        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);

    }


}
