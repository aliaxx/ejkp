<?php

namespace backend\modules\makanan\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;

/**
 * This is the model class for table "{{%SITAAN}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property string|null $JENISMAKANAN
 * @property string|null $PENGENALAN
 * @property int|null $KUANTITI
 * @property float|null $HARGA
 * @property string|null $KESALAHAN
 * @property string|null $NAMAPEMBUAT
 * @property string|null $ALAMATPEMBUAT
 * @property string|null $CATATAN
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class BarangSitaan extends \yii\db\ActiveRecord
{
    const IMAGE_PATH = 'images/SDR';
    public $files;
    public $namagambar;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%SITAAN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'JENISMAKANAN', 'KUANTITI', 'KESALAHAN', 'HARGA'], 'required'],
            [['KUANTITI', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            // [['NOSIRI'], 'string', 'max' => 20],
            [['NOSIRI', 'HARGA', 'JENISMAKANAN', 'PENGENALAN', 'KESALAHAN', 'NAMAPEMBUAT', 'ALAMATPEMBUAT', 'CATATAN', 'filename'], 'safe'],
            // [['JENISMAKANAN', 'PENGENALAN', 'KESALAHAN', 'NAMAPEMBUAT', 'ALAMATPEMBUAT', 'CATATAN'], 'string', 'max' => 255],
            [['image'], 'safe'],
            // [['image'], 'file', 'extensions'=>'jpg, gif, png'],
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
            'JENISMAKANAN' => 'Jenis Makanan',
            'PENGENALAN' => 'Tanda Pengenalan',
            'KUANTITI' => 'Kuantiti',
            'HARGA' => 'Harga',
            'KESALAHAN' => 'Kesalahan',
            'NAMAPEMBUAT' => 'Nama Pembuat',
            'ALAMATPEMBUAT' => 'Alamat Pembuat',
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
     * Get lists of file from folder 
     * @return array
     */
    // to display image in view - NOR18102022
    public function getAttachments()
    {
        $data = [];
        $path['folder'] = Yii::getAlias('@backend/web/' . self::IMAGE_PATH); 
        $path['web'] = Yii::getAlias('@web/' . self::IMAGE_PATH);

        $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));

        $models = BarangSitaan::findOne(['NOSIRI' => $this->NOSIRI, 'ID' => $this->ID]); 
        $id =  $models->ID;

        // $filesToSearch = $this->NOSIRI .'*';    
        $filesToSearch = $this->NOSIRI . '_' . $id .'*'; //will search and return image base on nosiri_id 
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

            $models = BarangSitaan::findOne(['NOSIRI' => $this->NOSIRI,'ID' => $this->ID]); //get current id of nosiri
            
            if(($this->ID == null)){
                //if id is null, count and assign to id
    
                $count = BarangSitaan::find()->count();
                $this->ID = ($count + 1);   
                $id = $this->ID;
            }else{
                //if id is not null, assign and use current id
                // var_dump('test2');
                // exit();

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

    public function getMakanan(){

        // return $this->hasMany(SampelMakanan::className(), ['NOSIRI' => 'NOSIRI'])->orderBy(['ID' => SORT_ASC])->limit(3);
        return $this->hasMany(BarangSitaan::className(), ['NOSIRI' => 'NOSIRI'])->orderBy(['ID' => SORT_ASC]);

    }

    public function getLesen(){

        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);

    }

    public function getMain(){

        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);

    }


}
