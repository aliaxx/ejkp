<?php

namespace backend\modules\vektor\models;
use common\models\LawatanMain;
use common\models\LawatanPasukan;
use common\models\LawatanPemilik;

use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\penyelenggaraan\models\Dun;
use backend\modules\integrasi\models\Sewa;
use backend\components\LogActions;


use Yii;

/**
 * This is the model class for table "{{%LAWATAN_MAIN}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property string|null $IDMODULE TBMODULE.PREFIX
 * @property int|null $JENISPREMIS 1 = SINGLE PREMIS, 2 = MULTIPLE PREMIS
 * @property int|null $IDZON_AM AHLIMAJLIS.V_ZON_AM.NOBOR_ZON
 * @property string|null $PRGNLOKASI_AM AHLIMAJLIS.V_ZON_AM.NAMA
 * @property string|null $PRGNLOKASI
 * @property int|null $IDDUN TBDUN.ID
 * @property int|null $ID_TUJUAN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 2
 * @property int|null $IDSUBUNIT TBPENGGUNA.SUBUNIT
 * @property string|null $TRKHMULA
 * @property string|null $TRKHTAMAT
 * @property string|null $NOADUAN
 * @property int|null $IDLOKASI IDMODULE = KP ONLY
 * @property string|null $LATITUD
 * @property string|null $LONGITUD
 * @property string|null $CATATAN
 * @property int|null $STATUS
 * @property int|null $STATUSREKOD
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property int|null $PP_PENGELUAR
 * @property int|null $PP_BILPENGENDALI
 * @property int|null $PP_SUNTIKAN_ANTITIFOID
 * @property int|null $PP_KURSUS_PENGENDALI
 * @property int|null $SM_ID_JENISSAMPEL TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 3
 * @property int|null $SI_ID_STOR TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 23
 * @property string|null $PK_NAMAPENYELIA
 * @property string|null $PK_NOKPPENYELIA
 * @property int|null $PK_JENISRAWATAN
 * @property string|null $PK_JENISKOLAM
 * @property int|null $PK_JUMPENGGUNA
 * @property string|null $ST_NOWABAK
 * @property string|null $ST_NOAKTIVITI
 * @property string|null $ST_RUJUKANKES
 * @property int|null $ST_ID_SEMBURANSRT
 * @property int|null $ST_TLENGKAPDALAM
 * @property int|null $ST_TLENGKAPLUAR
 * @property int|null $ST_LENGKAP
 * @property int|null $ST_TPERIKSA
 * @property int|null $ST_BILPENDUDUK
 * @property int|null $ST_SB1
 * @property int|null $ST_SB2
 * @property int|null $ST_SB3
 * @property int|null $ST_SB4
 * @property string|null $UV_PA_NODAFTARKES
 * @property string|null $UV_PA_LOKALITI
 * @property string|null $UV_PA_KAWASAN
 * @property string|null $UV_TRKHONSET
 * @property string|null $UV_TRKHKEYIN
 * @property string|null $UV_TRKHNOTIFIKASI
 * @property int|null $UV_PA_JENISSEMBUR
 * @property int|null $UV_PA_KATLOKALITI
 * @property int|null $UV_PA_PUSINGAN
 * @property int|null $UV_PA_ID_SUREVEILAN
 * @property int|null $UV_HUJAN
 * @property int|null $UV_KEADAANHUJAN
 * @property string|null $UV_MASAMULAHUJAN
 * @property string|null $UV_MASATAMATHUJAN
 * @property int|null $UV_ANGIN
 * @property int|null $UV_KEADAANANGIN
 * @property string|null $UV_MASAMULAANGIN
 * @property string|null $UV_MASATAMATANGIN
 * @property int|null $UV_JENISMESIN
 * @property int|null $UV_BILMESIN
 * @property int|null $UV_ID_RACUN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 10
 * @property float|null $UV_AMAUNRACUN
 * @property int|null $UV_ID_PELARUT TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 16
 * @property float|null $UV_AMAUNPELARUT
 * @property float|null $UV_AMAUNPETROL
 * @property string|null $PA_TRKHKEYINEDENGGI
 * @property string|null $PA_TRKHNOTIFIKASI
 * @property int|null $PA_MINGGUEPID
 * @property int|null $PA_SASARANPREMIS1
 * @property int|null $PA_BILPREMIS1
 * @property int|null $PA_BILBEKAS1
 * @property int|null $PA_ID_JENISRACUN1 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $PA_JUMRACUN1
 * @property int|null $PA_SASARANPREMIS2
 * @property int|null $PA_BILPREMIS2
 * @property int|null $PA_BILBEKAS2
 * @property int|null $PA_ID_JENISRACUN2 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $PA_JUMRACUN2
 * @property int|null $PA_BILORANG
 * @property int|null $PA_BILPREMIS3
 * @property int|null $PA_ID_JENISRACUN3 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $PA_JUMRACUN3
 * @property string|null $PA_NAMAKK
 * @property int|null $PA_TEMPOH
 * @property int|null $PA_ID_ALASAN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 13
 * @property int|null $PA_BILPENDUDUK
 * @property int|null $PA_BILBEKASMUSNAH
 * @property int|null $PA_TLENGKAPDALAM
 * @property int|null $PA_TLENGKAPLUAR
 * @property int|null $PA_LENGKAP
 * @property int|null $PA_TPERIKSA
 * @property int|null $PA_JUMSEBAB1
 * @property int|null $PA_JUMSEBAB2
 * @property int|null $PA_JUMSEBAB3
 * @property int|null $PA_JUMSEBAB4
 * @property int|null $PT_ID_JENISPREMISTANDAS TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 1
 * @property string|null $NAMAPENERIMA
 * @property string|null $NOKPPENERIMA
 * @property float|null $JUMKOMPAUN
 * @property float|null $JUMNOTIS
 */
class PenggredanTandas extends LawatanMain
{
    public $prgnidmodule;
    public $KETUAPASUKAN, $NAMA, $ahlipasukan;
    public $NOLESEN, $NOSSM, $NAMASYARIKAT, $NAMAPREMIS;
    public $ALAMAT1, $ALAMAT2, $ALAMAT3, $POSKOD, $NOTEL, $NAMAPEMOHON, $NOKPPEMOHON;
    public $KATEGORILESEN, $JENIS_PREMIS,$JENISJUALAN, $KETERANGANKATEGORI, $NOLESEN1,$KUMPULAN_LESEN, $KETERANGAN_KUMPULAN;
    public $JENISSEWA, $NOGERAI, $NOSEWA, $NOPETAK, $ID, $IDPEMILIK;
    public $KATPREMIS;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%LAWATAN_MAIN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['TRKHMULA', 'TRKHTAMAT', 'JENISPREMIS','ID_TUJUAN','PRGNLOKASI_AM','IDLOKASI'], 'required'],
            [['ID'], 'number'],
            [['NOSIRI', 'IDMODULE', 'PRGNLOKASI_AM', 'PRGNLOKASI', 'TRKHMULA', 'TRKHTAMAT', 'NOADUAN', 'LATITUD', 'LONGITUD', 'CATATAN', 'PK_NAMAPENYELIA', 'PK_NOKPPENYELIA', 'PK_JENISKOLAM', 'ST_NOWABAK', 'ST_NOAKTIVITI', 'ST_RUJUKANKES', 'UV_PA_NODAFTARKES', 'UV_PA_LOKALITI', 'UV_PA_KAWASAN', 'UV_TRKHONSET', 'UV_TRKHKEYIN', 'UV_TRKHNOTIFIKASI', 'UV_MASAMULAHUJAN', 'UV_MASATAMATHUJAN', 'UV_MASAMULAANGIN', 'UV_MASATAMATANGIN', 'PA_TRKHKEYINEDENGGI', 'PA_TRKHNOTIFIKASI', 'PA_NAMAKK'], 'safe'],
            [['JENISPREMIS', 'IDZON_AM', 'IDDUN', 'ID_TUJUAN', 'IDSUBUNIT', 'IDLOKASI', 'STATUS', 'STATUSREKOD', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'PP_PENGELUAR', 'PP_BILPENGENDALI', 'PP_SUNTIKAN_ANTITIFOID', 'PP_KURSUS_PENGENDALI', 'SM_ID_JENISSAMPEL', 'SI_ID_STOR', 'PK_JENISRAWATAN', 'PK_JUMPENGGUNA', 'ST_ID_SEMBURANSRT', 'ST_TLENGKAPDALAM', 'ST_TLENGKAPLUAR', 'ST_LENGKAP', 'ST_TPERIKSA', 'ST_BILPENDUDUK', 'ST_SB1', 'ST_SB2', 'ST_SB3', 'ST_SB4', 'UV_PA_JENISSEMBUR', 
            'UV_PA_KATLOKALITI', 'UV_PA_PUSINGAN', 'UV_PA_ID_SUREVEILAN', 'UV_HUJAN', 'UV_KEADAANHUJAN', 'UV_ANGIN', 'UV_KEADAANANGIN', 'UV_JENISMESIN', 'UV_BILMESIN', 'UV_ID_RACUN', 'UV_ID_PELARUT', 'PA_MINGGUEPID', 'PA_SASARANPREMIS1', 'PA_BILPREMIS1', 'PA_BILBEKAS1', 'PA_ID_JENISRACUN1', 'PA_SASARANPREMIS2', 'PA_BILPREMIS2', 'PA_BILBEKAS2', 'PA_ID_JENISRACUN2', 'PA_BILORANG', 'PA_BILPREMIS3', 'PA_ID_JENISRACUN3', 'PA_TEMPOH', 'PA_ID_ALASAN', 'PA_BILPENDUDUK', 'PA_BILBEKASMUSNAH', 
            'PA_TLENGKAPDALAM', 'PA_TLENGKAPLUAR', 'PA_LENGKAP', 'PA_TPERIKSA', 'PA_JUMSEBAB1', 'PA_JUMSEBAB2', 'PA_JUMSEBAB3', 'PA_JUMSEBAB4', 'PT_ID_JENISPREMISTANDAS'], 'integer'],
            [['NAMAPENERIMA','KETUAPASUKAN', 'ahlipasukan'], 'safe'],
            [['NOLESEN', 'NOSSM', 'NAMASYARIKAT', 'NAMAPREMIS', 'ALAMAT1', 'ALAMAT2', 'ALAMAT3', 'POSKOD', 
            'NOTEL', 'NAMAPEMOHON', 'NOKPPEMOHON','KATEGORILESEN', 'JENIS_PREMIS','JENISJUALAN','KETERANGANKATEGORI', 'NOLESEN1',
            'KUMPULAN_LESEN', 'KETERANGAN_KUMPULAN'], 'safe'],
            [['NOSEWA', 'NOGERAI', 'JENISSEWA', 'ID', 'IDPEMILIK'], 'safe'],
            [['ISTANDAS','STATUSLESENPREMIS', 'JENISCARIAN'], 'number'],
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
            // 'IDMODULE' => 'Idmodule',
            'JENISPREMIS' => 'Jenis Premis',
            'IDZON_AM' => 'Id Zon Ahli Majlis',
            'PRGNLOKASI_AM' => 'Lokasi Ahli Majlis',
            'PRGNLOKASI' => 'Keterangan Lokasi',
            'IDDUN' => 'Dun',
            'ID_TUJUAN' => 'Tujuan',
            'IDSUBUNIT' => 'Idsubunit',
            'TRKHMULA' => 'Tarikh Mula Lawatan',
            'TRKHTAMAT' => 'Tarikh Tamat Lawatan',
            'NOADUAN' => 'No Aduan',
            'IDLOKASI' => 'Lokasi',
            'LATITUD' => 'Latitud',
            'LONGITUD' => 'Longitud',
            'CATATAN' => 'Catatan',
            'STATUS' => 'Status',
            'STATUSREKOD' => 'Status Rekod',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
            'prgnidmodule'=> 'Aktiviti',
            'PP_PENGELUAR' => 'Pp  Pengeluar',
            'PP_BILPENGENDALI' => 'Pp  Bilpengendali',
            'PP_SUNTIKAN_ANTITIFOID' => 'Pp  Suntikan  Antitifoid',
            'PP_KURSUS_PENGENDALI' => 'Pp  Kursus  Pengendali',
            'SM_ID_JENISSAMPEL' => 'Sm  Id  Jenissampel',
            'SI_ID_STOR' => 'Si  Id  Stor',
            'PK_NAMAPENYELIA' => 'Pk  Namapenyelia',
            'PK_NOKPPENYELIA' => 'Pk  Nokppenyelia',
            'PK_JENISRAWATAN' => 'Pk  Jenisrawatan',
            'PK_JENISKOLAM' => 'Pk  Jeniskolam',
            'PK_JUMPENGGUNA' => 'Pk  Jumpengguna',
            'ST_NOWABAK' => 'St  Nowabak',
            'ST_NOAKTIVITI' => 'St  Noaktiviti',
            'ST_RUJUKANKES' => 'St  Rujukankes',
            'ST_ID_SEMBURANSRT' => 'St  Id  Semburansrt',
            'ST_TLENGKAPDALAM' => 'St  Tlengkapdalam',
            'ST_TLENGKAPLUAR' => 'St  Tlengkapluar',
            'ST_LENGKAP' => 'St  Lengkap',
            'ST_TPERIKSA' => 'St  Tperiksa',
            'ST_BILPENDUDUK' => 'St  Bilpenduduk',
            'ST_SB1' => 'St  Sb1',
            'ST_SB2' => 'St  Sb2',
            'ST_SB3' => 'St  Sb3',
            'ST_SB4' => 'St  Sb4',
            'UV_PA_NODAFTARKES' => 'Uv  Pa  Nodaftarkes',
            'UV_PA_LOKALITI' => 'Uv  Pa  Lokaliti',
            'UV_PA_KAWASAN' => 'Uv  Pa  Kawasan',
            'UV_TRKHONSET' => 'Uv  Trkhonset',
            'UV_TRKHKEYIN' => 'Uv  Trkhkeyin',
            'UV_TRKHNOTIFIKASI' => 'Uv  Trkhnotifikasi',
            'UV_PA_JENISSEMBUR' => 'Uv  Pa  Jenissembur',
            'UV_PA_KATLOKALITI' => 'Uv  Pa  Katlokaliti',
            'UV_PA_PUSINGAN' => 'Uv  Pa  Pusingan',
            'UV_PA_ID_SUREVEILAN' => 'Uv  Pa  Id  Sureveilan',
            'UV_HUJAN' => 'Uv  Hujan',
            'UV_KEADAANHUJAN' => 'Uv  Keadaanhujan',
            'UV_MASAMULAHUJAN' => 'Uv  Masamulahujan',
            'UV_MASATAMATHUJAN' => 'Uv  Masatamathujan',
            'UV_ANGIN' => 'Uv  Angin',
            'UV_KEADAANANGIN' => 'Uv  Keadaanangin',
            'UV_MASAMULAANGIN' => 'Uv  Masamulaangin',
            'UV_MASATAMATANGIN' => 'Uv  Masatamatangin',
            'UV_JENISMESIN' => 'Uv  Jenismesin',
            'UV_BILMESIN' => 'Uv  Bilmesin',
            'UV_ID_RACUN' => 'Uv  Id  Racun',
            'UV_AMAUNRACUN' => 'Uv  Amaunracun',
            'UV_ID_PELARUT' => 'Uv  Id  Pelarut',
            'UV_AMAUNPELARUT' => 'Uv  Amaunpelarut',
            'UV_AMAUNPETROL' => 'Uv  Amaunpetrol',
            'PA_TRKHKEYINEDENGGI' => 'Pa  Trkhkeyinedenggi',
            'PA_TRKHNOTIFIKASI' => 'Pa  Trkhnotifikasi',
            'PA_MINGGUEPID' => 'Pa  Mingguepid',
            'PA_SASARANPREMIS1' => 'Pa  Sasaranpremis1',
            'PA_BILPREMIS1' => 'Pa  Bilpremis1',
            'PA_BILBEKAS1' => 'Pa  Bilbekas1',
            'PA_ID_JENISRACUN1' => 'Pa  Id  Jenisracun1',
            'PA_JUMRACUN1' => 'Pa  Jumracun1',
            'PA_SASARANPREMIS2' => 'Pa  Sasaranpremis2',
            'PA_BILPREMIS2' => 'Pa  Bilpremis2',
            'PA_BILBEKAS2' => 'Pa  Bilbekas2',
            'PA_ID_JENISRACUN2' => 'Pa  Id  Jenisracun2',
            'PA_JUMRACUN2' => 'Pa  Jumracun2',
            'PA_BILORANG' => 'Pa  Bilorang',
            'PA_BILPREMIS3' => 'Pa  Bilpremis3',
            'PA_ID_JENISRACUN3' => 'Pa  Id  Jenisracun3',
            'PA_JUMRACUN3' => 'Pa  Jumracun3',
            'PA_NAMAKK' => 'Pa  Namakk',
            'PA_TEMPOH' => 'Pa  Tempoh',
            'PA_ID_ALASAN' => 'Pa  Id  Alasan',
            'PA_BILPENDUDUK' => 'Pa  Bilpenduduk',
            'PA_BILBEKASMUSNAH' => 'Pa  Bilbekasmusnah',
            'PA_TLENGKAPDALAM' => 'Pa  Tlengkapdalam',
            'PA_TLENGKAPLUAR' => 'Pa  Tlengkapluar',
            'PA_LENGKAP' => 'Pa  Lengkap',
            'PA_TPERIKSA' => 'Pa  Tperiksa',
            'PA_JUMSEBAB1' => 'Pa  Jumsebab1',
            'PA_JUMSEBAB2' => 'Pa  Jumsebab2',
            'PA_JUMSEBAB3' => 'Pa  Jumsebab3',
            'PA_JUMSEBAB4' => 'Pa  Jumsebab4',
            'PT_ID_JENISPREMISTANDAS' => 'Pt  Id  Jenispremistandas',
            'NAMAPENERIMA' => 'Namapenerima',
            'NOKPPENERIMA' => 'Nokppenerima',
            'JUMKOMPAUN' => 'Jumkompaun',
            'JUMNOTIS' => 'Jumnotis',
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

    public function setNosiri()
    {
        $curYear = date('y'); 
        // var_dump($curYear);

        // exit();
        $query = PenggredanTandas::find()->where(['IDMODULE' =>'PTS'])->orderBy(['NOSIRI' => SORT_DESC])->one();
        // var_dump($query);
        // exit();
        if (isset($query)) {
            $siri = substr($query->NOSIRI, 8); //4 stand for  bilangan of (Prefix)(Current year). PT22
            
            $siriid = (int)$siri + 1; 
            // var_dump($siriid);
            // exit();
            $siriid = sprintf("%05s", $siriid);
        } else {
            $siriid = '00001';
        }
        // var_dump($siriid);
        // exit();
        // $nosiri = 'KPN' . date('Y', strtotime($this->TRKHMULA)) . $siriid;
        $nosiri = 'PTS' .$curYear. $siriid;
        // var_dump($nosiri);
        // exit();

        $this->NOSIRI = $nosiri; //output

    }

    public function getKetuapasukan0()
    {

        return $this->hasOne(LawatanPasukan::className(), ['NOSIRI' => 'NOSIRI'])
            ->onCondition(['JENISPENGGUNA' => 1, '{{%LAWATAN_PASUKAN}}.STATUS' => 1])
            ->orderBy(['TURUTAN' => SORT_ASC]);

            // var_dump("here");
            // exit();
    }

    public function getPasukanAhlis()
    {
        return $this->hasMany(LawatanPasukan::className(), ['NOSIRI' => 'NOSIRI'])
            ->onCondition(['STATUS' => 1])
            ->orderBy(['TURUTAN' => SORT_ASC]);
    }

    public function saveAhliPasukan()
    {
        // var_dump($this->KETUAPASUKAN);
        // exit();

        if ($this->validate()) {

            // var_dump($this->ahlipasukan);
            // exit();
            
            $listNotIn = LawatanPasukan::find()->where([
                'NOSIRI' => $this->NOSIRI,
            ])->andWhere(['NOT IN', 'IDPENGGUNA', $this->ahlipasukan])
                ->andWhere(['<>', 'IDPENGGUNA', $this->KETUAPASUKAN])
                ->all();
       
            if ($listNotIn) {
                foreach ($listNotIn as $listAhli) {
                    // old data for logging update action
                    $oldmodel = json_encode($listAhli->getAttributes());

                    $listAhli->STATUS = 2;
                    $listAhli->save(false);

                    // record log
                    $log = new LogActions;
                    $log->recordLog($log::ACTION_UPDATE, $listAhli, $oldmodel);
                }
            }

            $log = new LogActions;

            $ketua = LawatanPasukan::findOne([
                'NOSIRI' => $this->NOSIRI,
                'IDPENGGUNA' => $this->KETUAPASUKAN,
            ]);
            
            // state log action type
            $action = $log::ACTION_UPDATE;
            
            if (!$ketua) {
                $ketua = new LawatanPasukan();
                $ketua->NOSIRI = $this->NOSIRI;

                // state log action type
                $action = $log::ACTION_CREATE;
            }

            // old data for logging update action
            $oldmodel = json_encode($ketua->getAttributes());

            $ketua->IDPENGGUNA = $this->KETUAPASUKAN;
            $ketua->IDMODULE= $this->IDMODULE;
            $ketua->JENISPENGGUNA = 1;
            $ketua->TURUTAN = 1;
            $ketua->save(false);

            // check if add new or update model
            if ($action == 1) {
                // record log
                $log->recordLog($action, $ketua);
            } else {
                // record log
                $log->recordLog($action, $ketua, $oldmodel);
            }

            if ($this->ahlipasukan) {
                $counterAhlipasukan = 2;

                foreach ($this->ahlipasukan as $key => $ahli) {
                    // exlcude ketuapasukan
                    
                    if ($ahli != $this->KETUAPASUKAN) {
                        $model = LawatanPasukan::findOne([
                            'NOSIRI' => $this->NOSIRI,
                            'IDPENGGUNA' => $ahli
                        ]);
                        
                        // state log action type
                        $action = $log::ACTION_UPDATE;

                        if (!$model) {
                            $model = new LawatanPasukan();
                            $model->NOSIRI = $this->NOSIRI;
                            
                            // state log action type
                            $action = $log::ACTION_CREATE;
                        }

                        // old data for logging update action
                        $oldmodel = json_encode($model->getAttributes());
                        // var_dump("dss");
                        // exit();

                        $model->IDPENGGUNA = $ahli;
                        $model->IDMODULE= $this->IDMODULE;
                        $model->JENISPENGGUNA = 2;
                        $model->TURUTAN = $counterAhlipasukan;
                        $model->STATUS = 1;
                        $model->save(false);
                        $counterAhlipasukan++;

                        // check if add new or update model
                        if ($action == 1) {
                            // record log
                            $log->recordLog($action, $model);
                        } else {
                            // record log
                            $log->recordLog($action, $model, $oldmodel);
                        }
                    }
                }
            }
            // var_dump($this->ahlipasukan);
            // exit();
            return true;
        }
        return false;
    }

    public function saveMaklumatLesen()
    {   
        if($this->NOSIRI){
            $model=LawatanPemilik::findOne(['NOSIRI'=>$this->NOSIRI]);

        }

            if(empty($model->NOSIRI))
            {
                $model=new LawatanPemilik();

                $model->NOSIRI = $this->NOSIRI;
                $model->NOLESEN= $this->NOLESEN1;
                $model->IDMODULE= $this->IDMODULE;
                $model->NOSSM= $this->NOSSM;
                $model->KATEGORI_LESEN= $this->KATEGORILESEN;
                $model->JENIS_JUALAN = $this->JENISJUALAN;
                $model->NAMAPEMOHON = $this->NAMAPEMOHON;
                $model->NOKPPEMOHON = $this->NOKPPEMOHON;
                $model->NAMASYARIKAT = $this->NAMASYARIKAT;
                $model->NAMAPREMIS = $this->NAMAPREMIS;
                $model->ALAMAT1 = $this->ALAMAT1;
                $model->ALAMAT2 = $this->ALAMAT2;
                $model->ALAMAT3 = $this->ALAMAT3;
                $model->POSKOD = $this->POSKOD;
                $model->JENIS_PREMIS = $this->JENIS_PREMIS;
                $model->JENIS_PREMIS = $this->JENIS_PREMIS;
                $model->NOTEL = $this->NOTEL;
                $model->STATUS = 1;
                $model->PGNDAFTAR = Yii::$app->user->id;
                $model->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
                $model->PGNAKHIR = Yii::$app->user->id;
                $model->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
                $model->KETERANGAN_KATEGORI= $this->KETERANGANKATEGORI;
                $model->KUMPULAN_LESEN= $this->KUMPULAN_LESEN;
                $model->KETERANGAN_KUMPULAN= $this->KETERANGAN_KUMPULAN;
                
                $model->save(false);
                    
            }
    }

    public function getPemilik0()
    {

        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getLokasiAm()
    {

        $sources = Yii::$app->db->createCommand("SELECT NOMBOR_ZON, NAMA FROM C##AHLIMAJLIS.V_ZON_AM")->queryAll();
       
        foreach ($sources as $source) {
            $output[] = ['id' => $source['NOMBOR_ZON'].'-'.$source['NAMA'], 'text' => $source['NAMA']];
        }
        return $output;

            // var_dump("here");
            // exit();
    }

    public function getTujuan0()
    {

        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_TUJUAN'])
            ->onCondition(['KODJENIS' => 22]);
    }

    public function getDun0()
    {
        return $this->hasOne(Dun::className(), ['ID' => 'IDDUN']);
    }

    public function getLokasi0()
    {
        return $this->hasOne(Sewa::className(), ['LOCATION_ID' => 'IDLOKASI']);
    }

    // public function getMarkahpremis()
    // {
    //     // $this->find()->where(['column'=>value])->sum('amount');
    //     // return $this->find()->where(['NOSIRI' => 'NOSIRI'])->sum('DEMERIT');

    //     $query = Yii::$app->db->createCommand("SELECT sum(DEMERIT) FROM TBGRED_PREMIS WHERE NOSIRI='$this->NOSIRI'");
    //     // $sum = $query->sum('DEMERIT')->where(['NOSIRI' => 'NOSIRI']);
    //     var_dump($query);
    //     exit();
    //     return $query;
    // } 
}
