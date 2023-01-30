<?php

namespace backend\modules\vektor\models;

use Yii;

/**
 * This is the model class for table "TBPV_PEMERIKSAANTANDAS".
 *
 * @property string $NOSIRI
 * @property string|null $NOLESEN
 * @property string|null $NOKPPEMOHON
 * @property string|null $NAMAPEMOHON
 * @property int|null $NOTEL
 * @property string|null $NOSSM
 * @property string|null $NAMASYARIKAT
 * @property string|null $ALAMAT1
 * @property string|null $ALAMAT2
 * @property string|null $ALAMAT3
 * @property string|null $POSKOD
 * @property string|null $TARIKHLAWATANMULA
 * @property string|null $TARIKHLAWATANTAMAT
 * @property int|null $KODJENIS_KATTANDAS
 * @property int|null $KODDETAIL_KATTANDAS
 * @property int|null $AHLIMAJLIS
 * @property int|null $LONGITUD
 * @property int|null $LATITUD
 * @property int|null $PEMERIKSA
 * @property string|null $NAMAPENERIMA
 * @property string|null $NOKPPENERIMA
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property string|null $NOSIRI_PP
 * @property string|null $NAMAPREMIS
 */
class PemeriksaanTandas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TBPV_PEMERIKSAANTANDAS';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
           // [['NOSIRI'], 'required'],
            [['NOTEL', 'KODJENIS_KATTANDAS', 'KODDETAIL_KATTANDAS', 'IDZONAM', 'PEMERIKSA', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOSSM', 'NOSIRI_PP'], 'string', 'max' => 20],
            [['NOLESEN'], 'string', 'max' => 45],
            [['LONGITUD', 'LATITUD'], 'string', 'max' => 20],
            [['NOKPPEMOHON', 'NOKPPENERIMA'], 'string', 'max' => 12],
            [['NAMAPEMOHON', 'NAMASYARIKAT', 'NAMAPENERIMA', 'NAMAPREMIS'], 'string', 'max' => 100],
            [['ALAMAT1', 'ALAMAT2', 'ALAMAT3'], 'string', 'max' => 70],
            [['POSKOD'], 'string', 'max' => 5],
           // [['TARIKHLAWATANMULA', 'TARIKHLAWATANTAMAT'], 'string', 'max' => 7],
            [['TARIKHLAWATANMULA', 'TARIKHLAWATANTAMAT'], 'safe'],
            [['NOSIRI'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NOSIRI' => 'No.Siri Rekod',
            'NOLESEN' => 'No.Lesen',
            'NOKPPEMOHON' => 'No.K/P Pemohon',
            'NAMAPEMOHON' => 'Nama Pemohon',
            'NAMAPREMIS' => 'Nama Premis',
            'NOTEL' => 'No.Telefon',
            'NOSSM' => 'No.Daftar Syarikat',
            'NAMASYARIKAT' => 'Nama Syarikat',
            'ALAMAT1' => 'Alamat Premis 1 ',
            'ALAMAT2' => 'Alamat Premis 2',
            'ALAMAT3' => 'Alamat Premis 3',
            'POSKOD' => 'Poskod',
            'TARIKHLAWATANMULA' => 'Tarikh Mula Pemeriksaan',
            'TARIKHLAWATANTAMAT' => 'Tarikh Tamat Pemeriksaan',
            // 'KODJENIS_KATTANDAS' => 'Kategori Premis',
            'KODDETAIL_KATTANDAS' => 'Kategori Premis',
            'IDZONAM' => 'Zon Ahli Majlis',
            'LONGITUD' => 'Longitud',
            'LATITUD' => 'Latitud',
            'PEMERIKSA' => 'Pegawai Pemeriksa',
            'NAMAPENERIMA' => 'Nama Penerima',
            'NOKPPENERIMA' => 'No.K/P /Passport Penerima',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pendaftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => '	ID Pengguna Kemaskini Terakhir ',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
            'NOSIRI_PP' => 'No.Siri Pemeriksaan Premis'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKategoriPremis()
    {
        return $this->hasOne(\backend\modules\penyelenggaraan\models\ParamDetail::className(), ['KODDETAIL' => 'KODDETAIL_KATTANDAS']);
    }

     /**
     * @return \yii\db\ActiveQuery
     */
    public function getAhliMajlis() //get data ID from tbahli majlis where id will insert to idzonam pemeriksaan tandas.
    {
        return $this->hasOne(\backend\modules\penyelenggaraan\models\ZonAhliMajlis::className(), ['ID' => 'IDZONAM']);
    }

      /**
     * @return \yii\db\ActiveQuery
     */
    public function getPegawaiPemeriksa()
    {
        return $this->hasOne(\common\models\Pengguna::className(), ['ID' => 'PEMERIKSA']);
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
     * Set for No Siri according to the format => Contoh : PT2200001 [(Prefix)(Current year)(id)]
     * This function will be called at TandasController
     * Current year akan depends on Tarikh Mula Lawatan.
     */

    public function setNosiri()
    {
        $query = PemeriksaanTandas::find()->where(['STATUS' => 1])->orderBy(['NOSIRI' => SORT_DESC])->one();
        if (isset($query)) {
            $siri = substr($query->NOSIRI, 4); //4 stand for  bilangan of (Prefix)(Current year). PT22
            $siriid = (int)$siri + 1; 
            $siriid = sprintf("%05s", $siriid);
        } else {
            $siriid = '00001';
        }

        $this->NOSIRI = 'PT' . date('y', strtotime($this->TARIKHLAWATANMULA)) . $siriid; //output
    }

    }
