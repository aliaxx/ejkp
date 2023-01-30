<?php

namespace backend\modules\integrasi\models;

use Yii;

/**
 * This is the model class for table "TBZON_AHLIMAJLIS".
 *
 * @property string $PRGNPANJANGZON
 * @property string $NAMAAHLIMAJLIS
 * @property string $PENGGAL
 * @property string $PRGNPANJANG
 * @property float $STATUS
 * @property float $ID
 * @property int $PGNDAFTAR
 * @property int $TRKHDAFTAR
 * @property int $PGNAKHIR
 * @property int $TRKHAKHIR
 */
class LesenMaster extends \yii\db\ActiveRecord  
{

    public $JENIS_LESEN,$AMAUN_LESEN,$LOKASI_MENJAJA,$JENIS_JUALAN,$KAWASAN,$ID_KAWASAN,$JENIS_JAJAAN;//declare variable of table V_EJKP_PENJAJA
    public $NOSEWA, $NOSEWA1;
    public $primaryKey = false;
    
     // get schema elesen
    // declare at common/config/main.php
    public static function getDb() {
        return Yii::$app->db2;
    }

    public static function tableName()
    {
        return '{{V_EJKP_MASTERLIST_LESEN}}';
    }

    //declare fake primary key
    public static function primaryKey()
    {
        return ['NO_AKAUN'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NAMA_PEMOHON', 'NO_KP_PEMOHON'], 'string', 'max' => 26],
            [['NO_AKAUN', 'ALAMAT_PREMIS3'], 'string', 'max' => 13],
            [['JENIS_PREMIS'], 'string', 'max' => 20],
            [['NAMA_SYARIKAT'], 'string', 'max' => 51],
            [['NO_DFT_SYKT', 'TARIKH_PERMOHONAN'], 'string', 'max' => 10],
            [['ID_PERMOHONAN'], 'string', 'max' => 6],
            [['ALAMAT_PREMIS1'], 'string', 'max' => 17],
            [['ALAMAT_PREMIS2'], 'string', 'max' => 29],
            [['POSKOD'], 'string', 'max' => 5],
            [['STATUS_PERMOHONAN', 'TARIKH_BATAL_TANGGUH'], 'string', 'max' => 1],
            [['KUMPULAN_LESEN', 'KATEGORI_LESEN'], 'string', 'max' => 3],
            [['KETERANGAN_KUMPULAN'], 'string', 'max' => 40],
            [['KETERANGAN_KATEGORI'], 'string', 'max' => 60],
            //[['NO_AKAUN'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'NO_AKAUN' => 'No.Akaun',
            'ID_PERMOHONAN' => 'ID Permohonan',
            'NO_KP_PEMOHON' => 'No.Kad Pengenalan',
            'NAMA_PEMOHON'=> 'Nama Pemohon',
            'NAMA_SYARIKAT'=> 'Nama Syarikat',
            'NO_DFT_SYKT'=> 'No.Pendaftaran Syarikat',
            'TARIKH_PERMOHONAN' => 'Tarikh Permohonan',
            'ALAMAT_PREMIS1' => 'Alamat Premis1',
            'ALAMAT_PREMIS2' => 'Alamat Premis2',
            'ALAMAT_PREMIS3' => 'Alamat Premis3',
            'POSKOD' => 'Poskod',
            'STATUS_PERMOHONAN' => 'Status Permohonan',
            'TARIKH_BATAL_TANGGUH' => 'Tarikh Batal',
            'KUMPULAN_LESEN' => 'Kumpulan Lesen',
            'KETERANGAN_KUMPULAN' => 'Keterangan Kumpulan',
            'KATEGORI_LESEN' => 'Kategori Lesen',
            'KETERANGAN_KATEGORI' => 'Keterangan Kategori',
            'JENIS_PREMIS' => 'Jenis Premis'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
        public function getPenjaja()
        {
            return $this->hasOne(\backend\modules\integrasi\models\Penjaja::class, ['NO_AKAUN' => 'NO_AKAUN']);
        }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLesen()
    {
        return $this->hasOne(\backend\modules\vektor\models\PemeriksaanTandas::class, ['NOLESEN' => 'NO_AKAUN']);
    }
}
