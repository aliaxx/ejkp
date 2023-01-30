<?php

namespace backend\modules\peniaga\models;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\lawatanmain\models\LawatanPemilik;


use Yii;

/**
 * This is the model class for table "{{%TRANS_GERAI}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property string $NOPETAK
 * @property string|null $TRKHLAWATAN_GERAI
 * @property string|null $NORUJUKAN
 * @property int|null $STATUSPEMANTAUAN
 * @property int|null $TINDAKANPENGUATKUASA
 * @property int|null $STATUSGERAI
 * @property int|null $PRGKP_GREASE
 * @property string|null $CATATAN
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Transgerai extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $NOSEWA,$NOLESEN;
    public $ALAMAT1, $ALAMAT2, $ALAMAT3, $POSKOD, $NOTEL, $NAMAPEMOHON, $NOKPPEMOHON;
    public $IDLOKASI, $JENISSEWA, $JENIS_JUALAN, $NOPETAK, $IDMODULE, $NOPETAK1;
    public $STATUSPEMANTAUAN_PRGN, $lokasi;

    const KOSONG = 0;
    const AB = 1;
    const TBS = 2;
    const TBL = 3;
    const CB = 4;

    public static function tableName()
    {
        return '{{%TRANS_GERAI}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI'], 'required'],
            [['TINDAKANPENGUATKUASA', 'STATUSGERAI', 'PRGKP_GREASE', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOPETAK', 'NORUJUKAN', 'IDPEMILIK'], 'string', 'max' => 20],
            [['TRKHLAWATAN_GERAI'], 'safe'],
            [['CATATAN'], 'string', 'max' => 255],
            [['ID'], 'unique', 'targetAttribute' => ['ID']],
            [['NOSEWA', 'NOLESEN', 'NOPETAK', 'ALAMAT1', 'ALAMAT2', 'ALAMAT3', 'POSKOD','NOTEL', 'NAMAPEMOHON','IDLOKASI', 'JENISSEWA', 'JENIS_JUALAN', 'IDPEMILIK', 'IDMODULE', 'NOPETAK1', 'NOKPPEMOHON'], 'safe'],
            [['STATUSPEMANTAUAN'], 'string', 'max' => 3],
            [['lokasi'], 'safe'],
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
            'NOPETAK' => 'No Gerai', //TAKDE DALAM TABLE
            'TRKHLAWATAN_GERAI' => 'Tarikh Lawatan Gerai',
            'NORUJUKAN' => 'No Rujukan',
            'STATUSPEMANTAUAN' => 'Status Pemantauan',
            'TINDAKANPENGUATKUASA' => 'Tindakan Penguatkuasa',
            'STATUSGERAI' => 'Keadaan Gerai',
            'PRGKP_GREASE' => 'Perangkap  Gris',
            'CATATAN' => 'Catatan',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
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


    public function getPemilikSewa0()
    {
        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI'])->orderBy(['ID' => SORT_DESC])->one();
    }

    public function getIdPemilik0()
    {
        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI','ID' => 'IDPEMILIK']);
    }

    public function getKpn0()
    {
        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);
    }

    // public function saveMaklumatPemilikGerai()
    // {
    //     $model=LawatanPemilik::findOne(['NOSIRI'=>$this->NOSIRI, 'ID'=>$this->IDPEMILIK]);

    //     if(empty($this->NOSIRI && $this->ID)){
          
    //         $modelpemilik=new LawatanPemilik();

    //         $modelpemilik->NOSIRI = $this->NOSIRI;
    //         $modelpemilik->IDMODULE= $this->IDMODULE;
    //         $modelpemilik->NOSEWA= $this->NOSEWA;
    //         $modelpemilik->NOLESEN= $this->NOLESEN;
    //         $modelpemilik->NOPETAK= $this->NOPETAK;
    //         $modelpemilik->JENISSEWA= $this->JENISSEWA;
    //         $modelpemilik->JENIS_JUALAN = $this->JENIS_JUALAN;
    //         $modelpemilik->NAMAPEMOHON = $this->NAMAPEMOHON;
    //         $modelpemilik->NOKPPEMOHON = $this->NOKPPEMOHON;
    //         $modelpemilik->ALAMAT1 = $this->ALAMAT1;
    //         $modelpemilik->ALAMAT2 = $this->ALAMAT2;
    //         $modelpemilik->ALAMAT3 = $this->ALAMAT3;
    //         $modelpemilik->POSKOD = $this->POSKOD;
    //         $modelpemilik->NOTEL = $this->NOTEL;
    //         $modelpemilik->STATUS = 1;
    //         $modelpemilik->PGNDAFTAR = Yii::$app->user->id;
    //         $modelpemilik->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
    //         $modelpemilik->PGNAKHIR = Yii::$app->user->id;
    //         $modelpemilik->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
    //         $modelpemilik->save(false);  

    //     }else{
    //         // var_dump('tiada rekod');
    //         // exit;

    //         $model->NOSEWA= $this->NOSEWA;
    //         $model->NOLESEN= $this->NOLESEN;
    //         $model->NOPETAK= $this->NOPETAK1;
    //         $model->JENISSEWA= $this->JENISSEWA;
    //         $model->JENIS_JUALAN = $this->JENIS_JUALAN;
    //         $model->NAMAPEMOHON = $this->NAMAPEMOHON;
    //         $model->NOKPPEMOHON = $this->NOKPPEMOHON;
    //         $model->ALAMAT1 = $this->ALAMAT1;
    //         $model->ALAMAT2 = $this->ALAMAT2;
    //         $model->ALAMAT3 = $this->ALAMAT3;
    //         $model->POSKOD = $this->POSKOD;
    //         $model->NOTEL = $this->NOTEL;
    //         // var_dump('<br>');
    //         // var_dump($model->NOTEL);
    //         // var_dump('<br>');
    //         // var_dump($this->NOTEL);
    //         // exit;
    //         $model->STATUS = 1;
    //         $model->PGNDAFTAR = Yii::$app->user->id;
    //         $model->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
    //         $model->PGNAKHIR = Yii::$app->user->id;
    //         $model->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
    //         $model->save(false);
    //     }   
    // }

    public function saveMaklumatPemilikGerai()
    {


        if($this->NOSIRI && $this->ID){

            $model=LawatanPemilik::findOne(['NOSIRI'=>$this->NOSIRI, 'ID'=>$this->IDPEMILIK]);

            $model->NOSEWA= $this->NOSEWA;
            $model->NOLESEN= $this->NOLESEN;
            $model->NOPETAK= $this->NOPETAK1;
            $model->JENISSEWA= $this->JENISSEWA;
            $model->JENIS_JUALAN = $this->JENIS_JUALAN;
            $model->NAMAPEMOHON = $this->NAMAPEMOHON;
            $model->NOKPPEMOHON = $this->NOKPPEMOHON;
            $model->ALAMAT1 = $this->ALAMAT1;
            $model->ALAMAT2 = $this->ALAMAT2;
            $model->ALAMAT3 = $this->ALAMAT3;
            $model->POSKOD = $this->POSKOD;

            

        }else{

            $model=new LawatanPemilik();

            $model->NOSIRI = $this->NOSIRI;
            $model->IDMODULE= $this->IDMODULE;
            $model->NOSEWA= $this->NOSEWA;
            $model->NOLESEN= $this->NOLESEN;
            $model->NOPETAK= $this->NOPETAK;
            $model->JENISSEWA= $this->JENISSEWA;
            $model->JENIS_JUALAN = $this->JENIS_JUALAN;
            $model->NAMAPEMOHON = $this->NAMAPEMOHON;
            $model->NOKPPEMOHON = $this->NOKPPEMOHON;
            $model->ALAMAT1 = $this->ALAMAT1;
            $model->ALAMAT2 = $this->ALAMAT2;
            $model->ALAMAT3 = $this->ALAMAT3;
            $model->POSKOD = $this->POSKOD;
            // exit;
                
        }

        // var_dump($this->NOTEL);
        // exit;

        $model->NOTEL = $this->NOTEL;
        
        $model->STATUS = 1;
        $model->PGNDAFTAR = Yii::$app->user->id;
        $model->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
        $model->PGNAKHIR = Yii::$app->user->id;
        $model->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));;
        
        $model->save(false);
       
    }

}
