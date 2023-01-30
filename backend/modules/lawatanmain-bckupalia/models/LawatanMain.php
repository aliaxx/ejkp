<?php

namespace backend\modules\lawatanmain\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\helpers\BaseFileHelper;
use yii\data\ActiveDataProvider;
use backend\components\LogActions;

use common\models\Pengguna;
use backend\modules\integrasi\models\Sewa;
// use backend\modules\vektor\models\Lokaliti;
use backend\modules\penyelenggaraan\models\Lokaliti;
use backend\modules\integrasi\models\Penjaja;
use backend\modules\penyelenggaraan\models\Dun;
use backend\modules\lawatanmain\models\LawatanPasukan;
use backend\modules\lawatanmain\models\LawatanPemilik;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\premis\models\Transpremis;

use backend\components\validator\LawatanMainTrkhMulaValidator;
use backend\components\validator\LawatanMainTrkhTamatValidator;
use backend\modules\vektor\models\SasaranUlv;
use backend\modules\vektor\models\SasaranPtp;
use backend\modules\vektor\models\BekasPtp;
use backend\modules\vektor\models\BekasLvc;
use backend\modules\vektor\models\SasaranLvc;
use backend\modules\vektor\models\SasaranSrt;


/**
 * This is the model class for table "TBLAWATAN_MAIN".
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
 * @property int|null $PPM_BILPENGENDALI
 * @property int|null $PPM_SUNTIKAN_ANTITIFOID
 * @property int|null $PPM_KURSUS_PENGENDALI
 * @property int|null $SMM_ID_JENISSAMPEL TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 6
 * @property int|null $SDR_ID_STOR TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 23
 * @property string|null $PKK_NAMAPENYELODAFTARKESIA
 * @property string|null $PKK_NOKPPENYELIA
 * @property int|null $PKK_JENISRAWATAN
 * @property string|null $PKK_JENISKOLAM
 * @property int|null $PKK_JUMPENGGUNA
 * @property string|null $V_NOWABAK
 * @property string|null $V_NOAKTIVITI
 * @property string|null $V_RUJUKANKES
 * @property int|null $SRT_ID_SEMBURANSRT
 * @property int|null $V_TLENGKAPDALAM
 * @property int|null $V_TLENGKAPLUAR
 * @property int|null $V_LENGKAP
 * @property int|null $V_TPERIKSA
 * @property int|null $V_BILPENDUDUK
 * @property int|null $V_SB1
 * @property int|null $V_SB2
 * @property int|null $V_SB3
 * @property int|null $V_SB4
 * @property string|null $V_NODAFTARKES
 * @property string|null $V_LOKALITI
 * @property string|null $ULV_TRKHONSET
 * @property string|null $V_TRKHKEYIN
 * @property string|null $V_TRKHNOTIFIKASI
 * @property int|null $V_JENISSEMBUR
 * @property int|null $V_KATLOKALITI
 * @property int|null $V_PUSINGAN
 * @property int|null $V_ID_SUREVEILAN
 * @property int|null $ULV_HUJAN
 * @property int|null $ULV_KEADAANHUJAN
 * @property string|null $ULV_MASAMULAHUJAN
 * @property string|null $ULV_MASATAMATHUJAN
 * @property int|null $ULV_ANGIN
 * @property int|null $ULV_KEADAANANGIN
 * @property string|null $ULV_MASAMULAANGIN
 * @property string|null $ULV_MASATAMATANGIN
 * @property int|null $ULV_JENISMESIN
 * @property int|null $ULV_BILMESIN
 * @property int|null $ULV_ID_RACUN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 10
 * @property float|null $ULV_AMAUNRACUN
 * @property int|null $ULV_ID_PELARUT TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 16
 * @property float|null $ULV_AMAUNPELARUT
 * @property float|null $ULV_AMAUNPETROL
 * @property int|null $V_MINGGUEPID
 * @property int|null $V_SASARANPREMIS1
 * @property int|null $V_BILPREMIS1
 * @property int|null $V_BILBEKAS1
 * @property int|null $V_ID_JENISRACUN1 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $V_JUMRACUN1
 * @property int|null $V_SASARANPREMIS2
 * @property int|null $V_BILPREMIS2
 * @property int|null $V_BILBEKAS2
 * @property int|null $V_ID_JENISRACUN2 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $V_JUMRACUN2
 * @property int|null $V_BILORANG
 * @property int|null $V_BILPREMIS3
 * @property int|null $V_ID_JENISRACUN3 TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 12
 * @property float|null $V_JUMRACUN3
 * @property string|null $PTP_NAMAKK
 * @property int|null $V_TEMPOH
 * @property int|null $V_ID_ALASAN TBPARAMETER_DETAIL.KODDETAIL WHERE KODJENIS = 13
 * @property int|null $PTP_BILBEKASMUSNAH
 * @property string|null $NAMAPENERIMA
 * @property string|null $NOKPPENERIMA
 * @property float|null $PTP_JUMKOMPAUN
 * @property float|null $PTP_JUMNOTIS
 * @property float|null $PTM_ISTANDAS
 * @property float|null $STATUSLESENPREMIS
 * @property float|null $JENISCARIAN
 * @property float|null $V_BILMESIN1
 * @property float|null $V_BILMESIN2
 */
class LawatanMain extends \yii\db\ActiveRecord
{    
    public $roles;
    public $prgnidmodule;
    public $TOTAL;
    public $files;
    public $namagambar;
    public $stampFrom, $stampTo;
    public $KETUAPASUKAN1, $KETUAPASUKAN, $NAMA, $ahlipasukan, $_inputahlipasukan;
    public $kodcawangan, $jenisahli;
    public $NOLESEN, $NOSSM, $NAMASYARIKAT, $NAMAPREMIS;
    public $ALAMAT1, $ALAMAT2, $ALAMAT3, $POSKOD, $NOTEL, $NAMAPEMOHON, $NOKPPEMOHON;
    public $KATEGORILESEN, $JENIS_PREMIS,$JENISJUALAN, $KETERANGANKATEGORI, $NOLESEN1,$KUMPULAN_LESEN, $KETERANGAN_KUMPULAN;
    public $JENISSEWA, $NOGERAI, $NOSEWA,$NOSEWA1,$LOKASI, $NOPETAK, $ID, $IDPEMILIK, $SUBUNIT;
    public $KATPREMIS;
    public $IDLOKASI1, $LOKASIPETAK;
    public $JENISTANDAS_ARR, $jenistandas;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%LAWATAN_MAIN}}';
    }

    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
    //         [['ID', 'ULV_AMAUNRACUN', 'ULV_AMAUNPELARUT', 'ULV_AMAUNPETROL', 'V_JUMRACUN1', 'V_JUMRACUN2', 'V_JUMRACUN3', 'PTP_JUMKOMPAUN', 
    //         'PTP_JUMNOTIS', 'PTM_ISTANDAS', 'STATUSLESENPREMIS', 'JENISCARIAN', 'V_BILMESIN1', 'V_BILMESIN2'], 'number'],
    //         [['TRKHMULA', 'TRKHTAMAT', 'NOSIRI','JENISPREMIS','ID_TUJUAN','PRGNLOKASI_AM','PRGNLOKASI','PKK_NAMAPENYELIA', 'PKK_NOKPPENYELIA', 
    //         'PKK_JENISKOLAM','PKK_JENISRAWATAN','ALAMAT1','KETUAPASUKAN', 'NAMAPENERIMA','NOKPPENERIMA', 
    //         'SMM_ID_JENISSAMPEL', 'SDR_ID_STOR','STATUSLESENPREMIS', 'NAMAPEMOHON', 'IDLOKASI'], 'required'],   
    //         [['JENISPREMIS', 'IDZON_AM', 'IDDUN', 'ID_TUJUAN', 'IDSUBUNIT', 'IDLOKASI', 'STATUS', 'STATUSREKOD', 'PGNDAFTAR', 'TRKHDAFTAR', 
    //         'PGNAKHIR', 'TRKHAKHIR', 'PPM_BILPENGENDALI', 'PPM_SUNTIKAN_ANTITIFOID', 'PPM_KURSUS_PENGENDALI', 'SMM_ID_JENISSAMPEL', 'SDR_ID_STOR', 
    //         'PKK_JENISRAWATAN', 'PKK_JUMPENGGUNA', 'SRT_ID_SEMBURANSRT', 'V_TLENGKAPDALAM', 'V_TLENGKAPLUAR', 'V_LENGKAP', 'V_TPERIKSA', 
    //         'V_SB1', 'V_SB2', 'V_SB3', 'V_SB4', 'V_JENISSEMBUR', 'V_KATLOKALITI', 'V_PUSINGAN', 'V_ID_SUREVEILAN', 'ULV_HUJAN', 'ULV_KEADAANHUJAN',
    //         'ULV_ANGIN', 'ULV_KEADAANANGIN', 'ULV_BILMESIN', 'ULV_ID_RACUN', 'ULV_ID_PELARUT', 'V_MINGGUEPID', 'V_SASARANPREMIS1', 
    //         'V_BILPREMIS1', 'V_BILBEKAS1', 'V_ID_JENISRACUN1', 'V_SASARANPREMIS2', 'V_BILPREMIS2', 'V_BILBEKAS2', 'V_ID_JENISRACUN2', 'V_BILORANG', 
    //         'V_BILPREMIS3', 'V_ID_JENISRACUN3', 'V_TEMPOH', 'V_ID_ALASAN', 'PTP_BILBEKASMUSNAH'], 'integer'],
    //         [['V_BILPENDUDUK'], 'number', 'max' => 9999999999],
    //         [['NOSIRI', 'V_NOWABAK', 'V_NOAKTIVITI', 'V_NODAFTARKES'], 'string', 'max' => 20],
    //         [['IDMODULE'], 'string', 'max' => 3],
    //         [['PRGNLOKASI_AM', 'PRGNLOKASI', 'CATATAN', 'V_RUJUKANKES', 'V_LOKALITI', 'PTP_NAMAKK', 'NAMAPENERIMA'], 'string', 'max' => 255],
    //         [['NOADUAN'], 'string', 'max' => 25],
    //         [['LATITUD', 'LONGITUD', 'PKK_NOKPPENYELIA', 'NOKPPENERIMA'], 'string', 'max' => 15],
    //         [['PKK_NAMAPENYELIA'], 'string', 'max' => 100],
    //         [['PKK_JENISKOLAM'], 'string', 'max' => 50],
    //         [['TRKHMULA', 'TRKHTAMAT'], 'safe'],
    //         [['ULV_TRKHONSET', 'V_TRKHKEYIN', 'V_TRKHNOTIFIKASI', 'ULV_MASAMULAHUJAN', 'ULV_MASATAMATHUJAN', 'ULV_MASAMULAANGIN', 'ULV_MASATAMATANGIN'], 'safe'],
    //         [['prgnidmodule', 'ULV_JENISMESIN', 'SMM_MAKMAL'],  'string', 'max' => 50],
    //         [['NOSIRI'], 'unique'],
    //         [['KETUAPASUKAN', 'ahlipasukan', '_inputahlipasukan', 'ahlipasukanselected'], 'safe'],
    //         [['NOLESEN', 'NOSSM', 'NAMASYARIKAT', 'NAMAPREMIS', 'ALAMAT1', 'ALAMAT2', 'ALAMAT3', 'POSKOD', 
    //         'NOTEL', 'NAMAPEMOHON', 'NOKPPEMOHON','KATEGORILESEN', 'JENIS_PREMIS','JENISJUALAN', 'KETERANGANKATEGORI', 'NOLESEN1'], 'safe'],
    //         [['KETUAPASUKAN1', 'JENISSEWA', 'NOGERAI', 'NOSEWA', 'NOPETAK', 'ID', 'IDPEMILIK', 'SUBUNIT'], 'safe'],
    //         [['IDLOKASI1','LOKASIPETAK', 'NOSEWA1', 'NOLESEN1', 'NOSSM'], 'safe'],
    //         [['PPM_IDKATPREMIS', 'PTS_JENISTANDAS', 'BILTANDAS'], 'safe'],
            
            
    //         [['stampFrom', 'stampTo'], 'safe'],
    //         ['TRKHMULA', 'datetime', 'timestampAttribute' => 'stampFrom', 'format' => 'php:Y-m-d H:i:s'],
    //         ['TRKHTAMAT', 'datetime', 'timestampAttribute' => 'stampTo', 'format' => 'php:Y-m-d H:i:s'],

    //         ['TRKHMULA', LawatanMainTrkhMulaValidator::className()],
    //         ['TRKHTAMAT', LawatanMainTrkhTamatValidator::className()],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NOSIRI' => 'No Siri Rekod',
            'IDMODULE' => 'Aktiviti',
            'JENISPREMIS' => 'Kategori',
            'IDZON_AM' => 'Id Zon Ahli Majlis',
            'PRGNLOKASI_AM' => 'Lokasi Ahli Majlis',
            'IDLOKASI' => 'Lokasi Penjaja',
            'PRGNLOKASI' => 'Lokasi',
            'IDDUN' => 'Dun',
            'ID_TUJUAN' => 'Tujuan',
            'IDSUBUNIT' => 'Id Sub Unit',
            'TRKHMULA' => 'Tarikh Mula Lawatan',
            'TRKHTAMAT' => 'Tarikh Tamat Lawatan',
            'NOADUAN' => 'No. Aduan',
            'LATITUD' => 'Latitud',
            'LONGITUD' => 'Longitud',
            'CATATAN' => 'Catatan Pemeriksaan',
            'STATUS' => 'Status',
            'STATUSREKOD' => 'Status Rekod',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => '	Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
            'PPM_BILPENGENDALI' => 'Bil. Pengendali',
            'PPM_SUNTIKAN_ANTITIFOID' => 'Suntikan Pelalian Anti-Tifoid',
            'PPM_KURSUS_PENGENDALI' => 'Kursus Pengendali Makanan',
            'SMM_ID_JENISSAMPEL' => 'Jenis Persampelan',
            'SDR_ID_STOR' => 'Tempat Simpanan',
            'PKK_NAMAPENYELIA' => 'Nama Penyelia/Operator',
            'PKK_NOKPPENYELIA' => 'No. KP Penyelia',
            'PKK_JENISRAWATAN' => 'Jenis Rawatan Air Kolam',
            'PKK_JENISKOLAM' => 'Jenis Kolam',            
            'PKK_JUMPENGGUNA' => 'Anggaran Jumlah Pengguna Ketika Persampelan Dilakukan',
            'V_NOWABAK' => 'No. Wabak',
            'V_NOAKTIVITI' => 'No. Aktiviti',
            'V_RUJUKANKES' => 'Nama Rujukan Kes',
            'SRT_ID_SEMBURANSRT' => 'Jenis Semburan',
            'V_TLENGKAPDALAM' => 'Tidak lengkap Dalam',
            'V_TLENGKAPLUAR' => 'Tidak Lengkap Dalam',
            'V_LENGKAP' => 'Lengkap',
            'V_TPERIKSA' => 'Jumlah Premis Tidak Diperiksa',
            'V_BILPENDUDUK' => 'Bilangan Penduduk',
            'V_SB1' => 'Penduduk Enggan',
            'V_SB2' => 'Premis Kosong',
            'V_SB3' => 'Premis Tutup',
            'V_SB4' => 'Lain-lain',
            'V_NODAFTARKES' => 'No. Daftar Kes',
            'V_LOKALITI' => 'Lokaliti',
            'ULV_TRKHONSET' => 'Tarikh Onset',
            'V_TRKHKEYIN' => 'Tarikh Data Dimasukkan',
            'V_TRKHNOTIFIKASI' => 'Tarikh Notifikasi Kes',
            'V_JENISSEMBUR' => 'Jenis Semburan',
            'V_KATLOKALITI' => 'Kategori Lokaliti',
            'V_PUSINGAN' => 'Pusingan',
            'V_ID_SUREVEILAN' => 'Surveilan',
            'ULV_HUJAN' => 'Hujan',
            'ULV_KEADAANHUJAN' => 'Keadaan Hujan',
            'ULV_MASAMULAHUJAN' => 'Dari',
            'ULV_MASATAMATHUJAN' => 'Sehingga',
            'ULV_ANGIN' => 'Angin',
            'ULV_KEADAANANGIN' => 'Keadaan Angin',
            'ULV_MASAMULAANGIN' => 'Dari',
            'ULV_MASATAMATANGIN' => 'Sehingga',
            'ULV_JENISMESIN' => 'Jenis Mesin ULV',
            'ULV_BILMESIN' => 'Bil. Mesin ULV yang Digunakan',
            'ULV_ID_RACUN' => 'Jenis Racun Serangga',
            'ULV_AMAUNRACUN' => 'Amaun Racun Serangga',
            'ULV_ID_PELARUT' => 'Jenis Pelarut',
            'ULV_AMAUNPELARUT' => 'Amaun Pelarut',
            'ULV_AMAUNPETROL' => 'Amaun Petrol',
            'V_MINGGUEPID' => 'Minggu Epid',
            'V_SASARANPREMIS1' => 'Sasaran Premis',
            'V_BILPREMIS1' => 'Bil. Premis',
            'V_BILBEKAS1' => 'Bil. Bekas',
            'V_ID_JENISRACUN1' => 'Jenis Racun',
            'V_JUMRACUN1' => 'Jum. Racun (gm/ml)',
            'V_SASARANPREMIS2' => 'Sasaran Premis',
            'V_BILPREMIS2' => 'Bil. Premis',
            'V_BILBEKAS2' => 'Bil. Bekas',
            'V_ID_JENISRACUN2' => 'Jenis Racun',
            'V_JUMRACUN2' => 'Jum. Racun (gm/ml)',
            'V_BILORANG' => 'Bil. Orang',
            'V_BILPREMIS3' => 'Bil. Premis',
            'V_ID_JENISRACUN3' => 'Jenis Racun',
            'V_JUMRACUN3' => 'Jum. Racun (gm/ml)',
            'PTP_NAMAKK' => 'Nama KK',
            'V_TEMPOH' => 'Tempoh Masa Pemeriksaan Selepas Notifikasi Kes',
            'V_ID_ALASAN' => 'Alasan',
            'PTP_BILBEKASMUSNAH' => 'Bil Bekas Musnah',
            'NAMAPENERIMA' => 'Nama Penerima',
            'NOKPPENERIMA' => 'No. KP Penerima',
            'PTP_JUMKOMPAUN' => 'Jumlah Kompaun',
            'PTP_JUMNOTIS' => 'Jumlah Notis',
            'PTM_ISTANDAS' => 'Pemeriksaan Tandas',
            'STATUSLESENPREMIS' => 'Status Lesen Premis',
            'JENISCARIAN' => 'Jenis Carian',
            'V_BILMESIN1' => 'Bil. Mesin',
            'V_BILMESIN2' => 'Bil. Mesin',
            'NOLESEN'=> 'No. Lesen',
            'NOSSM'=> 'No. Daftar SSM',
            'NOKPPEMOHON' => 'No. KP Pemilik/Pemohon',
            'NAMASYARIKAT'=> 'Nama Syarikat',
            'JENIS_PREMIS' => 'Jenis Premis', 
            'NAMAPREMIS' => 'Nama Premis', 
            'NAMAPEMOHON' => 'Nama Pemilik/Pemohon',
            'JENISJUALAN' => 'Jenis Jualan',
            'prgnidmodule'=> 'Aktiviti',
            'KETUAPASUKAN' => 'Ketua Pasukan',
            'ahlipasukan' => 'Ahli Pasukan',
            'SMM_MAKMAL' => 'Makmal Analisis Sampel',
            'PPM_IDKATPREMIS' => 'Kategori Premis'
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
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
     * @return \yii\db\ActiveQuery
     */
    public function getIdmodule()
    {
        return $this->hasOne(\backend\models\Module::className(), ['ID' => 'IDMODULE']);
    }
    
    public function getPemilik0()
    {
        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getLokasiAm()
    {
        $sources = Yii::$app->db->createCommand("SELECT NOMBOR_ZON, NAMA FROM AHLIMAJLIS.V_ZON_AM")->queryAll();
       
        foreach ($sources as $source) {
            $output[] = ['id' => $source['NOMBOR_ZON'].'-'.$source['NAMA'], 'text' => $source['NAMA']];
        }
        return $output;
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

    public function getJenis()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'SMM_ID_JENISSAMPEL'])
            ->onCondition(['KODJENIS' => 6]);
    }

    public function getStor()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'SDR_ID_STOR'])
            ->onCondition(['KODJENIS' => 24]);
    }

    public function getLokasi0()
    {
        return $this->hasOne(Sewa::className(), ['LOCATION_ID' => 'IDLOKASI']);
    }

    public function getGerai0()
    {
        return $this->hasOne(TransGerai::className(), ['NOSIRI' => 'NOSIRI']);
    }

    //to get subunit
    public function getSubunit0()
    {
       //  var_dump($this->hasOne(Pengguna::className(), ['SUBUNIT' => 'IDSUBUNIT']));
        return $this->hasOne(Pengguna::className(), ['SUBUNIT' => 'IDSUBUNIT']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLokaliti()
    {
        return $this->hasOne(Lokaliti::className(), ['ID' => 'V_LOKALITI']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisSemburan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'SRT_ID_SEMBURANSRT'])
            ->onCondition(['KODJENIS' => '7']);
    }

    public function getRacun()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ULV_ID_RACUN'])
            ->onCondition(['KODJENIS' => 10]);
    }

    public function getPelarut()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ULV_ID_PELARUT'])
            ->onCondition(['KODJENIS' => 16]);
    }

    public function getAlasan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'V_ID_ALASAN'])
            ->onCondition(['KODJENIS' => 13]);
    }

    /**
     * Set for No Siri according to the format => Contoh : PT2200001 [(Prefix)(Current year)(id)]
     * This function will be called at TandasController
     * Current year akan depends on Tarikh Mula Lawatan.
     */
    public function setNosiri($idmodule)
    {
        $curYear = date('y'); 
        $query = LawatanMain::find()->where(['IDMODULE' =>$idmodule])->orderBy(['NOSIRI' => SORT_DESC])->one();

        if (isset($query)) {
            $siri = substr($query->NOSIRI, 8); //4 stand for  bilangan of (Prefix)(Current year). PT22
            
            $siriid = (int)$siri + 1; 
            $siriid = sprintf("%05s", $siriid);
        } else {
            $siriid = '00001';
        }
        
        $nosiri = $idmodule .$curYear. $siriid;
        $this->NOSIRI = $nosiri; //output
    }    

    public function getKetuapasukan0()
    {
        return $this->hasOne(LawatanPasukan::className(), ['NOSIRI' => 'NOSIRI'])
            ->onCondition(['JENISPENGGUNA' => 1, '{{%LAWATAN_PASUKAN}}.STATUS' => 1])
            ->orderBy(['TURUTAN' => SORT_ASC]);
    }

    public function getPengguna0()
    {
        return $this->hasOne(Pengguna::className(), ['ID' => 'IDPENGGUNA']);
    }

    public function getPasukanAhlis()
    {
        return $this->hasMany(LawatanPasukan::className(), ['NOSIRI' => 'NOSIRI'])
            ->onCondition(['STATUS' => 1])
            ->orderBy(['TURUTAN' => SORT_ASC]);
    }

    public function saveAhliPasukan()
    {
        //if ($this->validate()) {
        if ($this->load(Yii::$app->request->post())) {

            $listNotIn = LawatanPasukan::find()->where([
                'NOSIRI' => $this->NOSIRI,
            ])->andWhere(['NOT IN', 'IDPENGGUNA', $this->ahlipasukan])
                ->andWhere(['<>', 'IDPENGGUNA', $this->KETUAPASUKAN])
                ->all();
       
            if ($listNotIn) {
                foreach ($listNotIn as $listAhli) {
                    // old data for logging update action
                    // $oldmodel = json_encode($listAhli->getAttributes());

                    $listAhli->STATUS = 2;
                    $listAhli->save(false);

                    // record log
                    // $log = new LogActions;
                    // $log->recordLog($log::ACTION_UPDATE, $listAhli, $oldmodel);
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

                        $model->IDPENGGUNA = $ahli;
                        $model->IDMODULE= $this->IDMODULE;
                        $model->JENISPENGGUNA = 2;
                        $model->TURUTAN = $counterAhlipasukan;
                        $model->STATUS = 1;
                        $counterAhlipasukan++;
                        
                        $model->save(false);

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

            return true;
        }
        return false;
    }

    //save maklumat lesen & sewa to TBLAWATANPEMILIK
    public function saveMaklumatPemilik()
    {
        $model=LawatanPemilik::findOne(['NOSIRI'=>$this->NOSIRI]);

        //new record
        if(empty($model->NOSIRI))
        {
            $modelpemilik=new LawatanPemilik();

            $modelpemilik->NOSIRI = $this->NOSIRI;
            $modelpemilik->NOLESEN= $this->NOLESEN1;
            $modelpemilik->IDMODULE= $this->IDMODULE;
            $modelpemilik->NOSSM= $this->NOSSM;
            $modelpemilik->KATEGORI_LESEN= $this->KATEGORILESEN;
            $modelpemilik->JENIS_JUALAN = $this->JENISJUALAN;
            $modelpemilik->NAMAPEMOHON = $this->NAMAPEMOHON;
            $modelpemilik->NOKPPEMOHON = $this->NOKPPEMOHON;
            $modelpemilik->NAMASYARIKAT = $this->NAMASYARIKAT;
            $modelpemilik->NAMAPREMIS = $this->NAMAPREMIS;
            $modelpemilik->ALAMAT1 = $this->ALAMAT1;
            $modelpemilik->ALAMAT2 = $this->ALAMAT2;
            $modelpemilik->ALAMAT3 = $this->ALAMAT3;
            $modelpemilik->POSKOD = $this->POSKOD;
            $modelpemilik->JENIS_PREMIS = $this->JENIS_PREMIS;
            $modelpemilik->NOTEL = $this->NOTEL;
            $modelpemilik->KETERANGAN_KATEGORI= $this->KETERANGANKATEGORI;
            $modelpemilik->KUMPULAN_LESEN= $this->KUMPULAN_LESEN;
            $modelpemilik->KETERANGAN_KUMPULAN= $this->KETERANGAN_KUMPULAN;
            $modelpemilik->NOSEWA= $this->NOSEWA1;
            $modelpemilik->NOPETAK= $this->NOPETAK;
            $modelpemilik->LOKASIPETAK= $this->LOKASIPETAK;
            $modelpemilik->JENISSEWA= $this->JENISSEWA;
            $modelpemilik->STATUS = 1;
            $modelpemilik->PGNDAFTAR = Yii::$app->user->id;
            $modelpemilik->TRKHDAFTAR = strtotime(date("Y-m-d H:i:s"));
            $modelpemilik->PGNAKHIR = Yii::$app->user->id;
            $modelpemilik->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
            $modelpemilik->save(false);     
        }

        else{//kemaskini maklumat lesen & sewa

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
            $model->NOTEL = $this->NOTEL;
            $model->KETERANGAN_KATEGORI= $this->KETERANGANKATEGORI;
            $model->KUMPULAN_LESEN= $this->KUMPULAN_LESEN;
            $model->KETERANGAN_KUMPULAN= $this->KETERANGAN_KUMPULAN;
            $model->NOSEWA= $this->NOSEWA1;
            $model->NOPETAK= $this->NOPETAK;
            $model->LOKASIPETAK= $this->LOKASIPETAK;
            $model->JENISSEWA= $this->JENISSEWA;

            $model->STATUS = 1;
            $model->PGNAKHIR = Yii::$app->user->id;
            $model->TRKHAKHIR = strtotime(date("Y-m-d H:i:s"));
            $model->save(false);     
        }
    }  
    
    

    /**
     * Get lists of file from folder 
     * @return array
     */
    public function getAttachments($idmodule)
    {
        $query = LawatanMain::find()->where(['IDMODULE' =>$idmodule])->orderBy(['NOSIRI' => SORT_DESC])->one();
        $data = [];

            //Get files dari folder mengikut idmodule.
            if ($query->IDMODULE ==$idmodule){
                $path['folder'] = Yii::getAlias('@backend/web/images/'.$idmodule);
                $path['web'] = Yii::getAlias('@web/images/'.$idmodule);
            } 
           
            $filesToSearch = $this->NOSIRI .'*';       
            
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
    
    public function saveAttachment($idmodule)
    {
        if ($this->files) {
            // var_dump('go here');
            // exit;

            $today = date('YmdHis'); //date('Y-m-d', strtotime($trkhTamat));
            $query = LawatanMain::find()->where(['IDMODULE' =>$idmodule])->orderBy(['NOSIRI' => SORT_DESC])->one();
            $data = [];

            if ($query->IDMODULE == $idmodule){
                $path['folder'] = Yii::getAlias('@backend/web/images/'.$idmodule);
                $path['web'] = Yii::getAlias('@web/images/'.$idmodule);
            }
        
                foreach ($this->files as $file) {
                    $filename = $this->NOSIRI . '_'.$file->basename . $today . '.' . $file->extension;

                    $path['file'] = $path['folder'] . '/' . $filename;
                    if ($file->saveAs($path['file'])) {
                        $data[] = $path['web'] . '/' . $filename;
                    }
                    
                        // record log
                        $log = new LogActions;
                        $log->recordLog($log::ACTION_UPLOAD, $query);
                }
        }
        return $data;
    }


    public function deleteFile($idmodule, $nosiri, $filename)
    {
        $query = LawatanMain::find()->where(['IDMODULE' =>$idmodule])->orderBy(['NOSIRI' => SORT_DESC])->one();
        $data = [];

        if ($query->IDMODULE == $idmodule){
            $path['folder'] = Yii::getAlias('@backend/web/images/'.$idmodule);
            $path['web'] = Yii::getAlias('@web/images/'.$idmodule);
        }  

        $data = $path['folder'] . '/' . $filename;
      
        // Use unlink() function to delete a file
        if (@unlink($data)) {
            return true;
        }else { 
            return false;
        }
    }

    public function getAhli() //nor11112022
    {
        return $this->hasMany(LawatanPasukan::className(), ['NOSIRI' => 'NOSIRI'])
            ->onCondition(['STATUS' => 1, 'JENISPENGGUNA' => 2])
            ->orderBy(['TURUTAN' => SORT_ASC]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getSasaranulv() //nor11112022
    {
        return $this->hasMany(SasaranUlv::className(), ['NOSIRI' => 'NOSIRI']);  
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getSasaranptp() //nor11112022
    {
        return $this->hasMany(SasaranPtp::className(), ['NOSIRI' => 'NOSIRI']);  
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getSasaranlvc() //nor16112022
    {
        return $this->hasMany(SasaranLvc::className(), ['NOSIRI' => 'NOSIRI']);  
    }

    public function getMukim() //-nor10112022
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'ID_MUKIM'])
        ->onCondition(['KODJENIS' => 11])
        ->via('dun0');
    }

    public function getBekas1() //-nor14112022
    {
        return $this->hasMany(BekasPtp::className(), ['NOSIRI' => 'NOSIRI'])
        ->onCondition(['KAWASAN' => 1]);
    }

    public function getBekas2() //-nor14112022
    {
        return $this->hasMany(BekasPtp::className(), ['NOSIRI' => 'NOSIRI'])
        ->onCondition(['KAWASAN' => 2]);
    }

    public function getBekasptp() //-nor15112022
    {
        return $this->hasMany(BekasPtp::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getBekaslvc1() //-nor14112022
    {
        return $this->hasMany(BekasLvc::className(), ['NOSIRI' => 'NOSIRI'])
        ->onCondition(['KAWASAN' => 1]);
    }

    public function getBekaslvc2() //-nor14112022
    {
        return $this->hasMany(BekasLvc::className(), ['NOSIRI' => 'NOSIRI'])
        ->onCondition(['KAWASAN' => 2]);
    }

    public function getBekaslvc() //-nor15112022
    {
        return $this->hasMany(BekasLvc::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getRacun1() //-nor15112022
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'V_ID_JENISRACUN1'])
            ->onCondition(['KODJENIS' => 10]);
    }

    public function getRacun2() //-nor15112022
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'V_ID_JENISRACUN2'])
            ->onCondition(['KODJENIS' => 10]);
    }

    public function getRacun3() //-nor15112022
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'V_ID_JENISRACUN3'])
            ->onCondition(['KODJENIS' => 10]);
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getSasaransrt() //nor25112022
    {
        return $this->hasMany(SasaranSrt::className(), ['NOSIRI' => 'NOSIRI']);  
    }

    public function getMakmal() //-nor30112022
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'SMM_MAKMAL'])
            ->onCondition(['KODJENIS' => 26]);
    }

    public function getPremis0() 
    {
        return $this->hasOne(Transpremis::className(), ['NOSIRI' => 'NOSIRI'])->via('pemilik0');
    }

    public function getKatpremis0()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'PPM_IDKATPREMIS'])
            ->onCondition(['KODJENIS' => 1]);
    }


    // public function setJenisTandas() //alia03012023
    // {
    //     LawatanMain::deleteAll(['NOSIRI' => $this->NOSIRI]);
    //     // for ($i = 0; $i < count($this->PTS_JENISTANDAS); $i++) {
    //         $model = new LawatanMain();
    //         $model->PTS_JENISTANDAS =  implode(',', $model->PTS_JENISTANDAS); //save jenis tandas
    //         $model->BILTANDAS =  implode(',', $model->BILTANDAS); //save bilangan tandas
    //         $model->save(false);
    //     // }
    // }

     /**
     * @return yii\db\ActiveQuery
     * tambah text dekat item_jenistandas (counter)
     */
    public function getCountertandas()
    {
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID');  
    }

    public function getAb()
    {
        return $this->hasMany(TransTandas::className(), ['NOSIRI' => 'NOSIRI']);
    }

}
