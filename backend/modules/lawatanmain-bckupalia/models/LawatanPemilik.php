<?php

namespace backend\modules\lawatanmain\models;

use Yii;

/**
 * This is the model class for table "{{%LAWATAN_PEMILIK}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property string|null $IDMODULE
 * @property int|null $SEARCHTYPE
 * @property int|null $JENISPREMIS
 * @property string|null $NOLESEN
 * @property string|null $KATEGORI_LESEN
 * @property string|null $KUMPULAN_LESEN
 * @property string|null $JENIS_JUALAN
 * @property string|null $JENIS_PREMIS
 * @property string|null $NOSSM
 * @property string|null $NAMASYARIKAT
 * @property string|null $NAMAPREMIS
 * @property string|null $NAMAPEMOHON
 * @property string|null $NOKPPEMOHON
 * @property string|null $ALAMAT1
 * @property string|null $ALAMAT2
 * @property string|null $ALAMAT3
 * @property string|null $POSKOD
 * @property string|null $NOTEL
 * @property string|null $NOSEWA
 * @property string|null $NOPETAK
 * @property string|null $LOKASIPETAK
 * @property string|null $JENISSEWA
 * @property string|null $NAMAPENERIMA
 * @property string|null $NOKPPENERIMA
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class LawatanPemilik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $NOLESEN1, $NOSEWA1;
    public static function tableName()
    {
        return '{{%LAWATAN_PEMILIK}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI'], 'required'],
            [['SEARCHTYPE', 'JENISPREMIS', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOTEL'], 'string', 'max' => 20],
            [['IDMODULE'], 'string', 'max' => 3],
            [['NOLESEN', 'NOSEWA', 'NOPETAK'], 'string', 'max' => 50],
            [['KATEGORI_LESEN', 'KUMPULAN_LESEN', 'JENIS_JUALAN', 'JENIS_PREMIS', 'NOSSM', 'NAMASYARIKAT', 'NAMAPREMIS', 'NAMAPEMOHON', 'LOKASIPETAK', 
                'JENISSEWA', 'NAMAPENERIMA'], 'string', 'max' => 255],
            [['NOKPPEMOHON', 'NOKPPENERIMA'], 'string', 'max' => 15],
            [['ALAMAT1'], 'string', 'max' => 500],
            [['ALAMAT2'], 'string', 'max' => 300],
            [['ALAMAT3'], 'string', 'max' => 100],
            [['POSKOD'], 'string', 'max' => 10],
            [['NOSIRI'], 'unique'],
            [['NOLESEN1', 'NOSEWA1'], 'safe'],
            [['KETERANGAN_KATEGORI', 'KETERANGAN_KUMPULAN'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'NOSIRI' => 'Nosiri',
            'IDMODULE' => 'Idmodule',
            'SEARCHTYPE' => 'Searchtype',
            'JENISPREMIS' => 'Jenispremis',
            'NOLESEN' => 'No Lesen',
            // 'NOLESEN1' => 'No Lesen',
            'KATEGORI_LESEN' => 'Kategori  Lesen',
            'KUMPULAN_LESEN' => 'Kumpulan  Lesen',
            'JENIS_JUALAN' => 'Jenis  Jualan',
            'JENIS_PREMIS' => 'Jenis  Premis',
            'NOSSM' => 'Nossm',
            'NAMASYARIKAT' => 'Namasyarikat',
            'NAMAPREMIS' => 'Namapremis',
            'NAMAPEMOHON' => 'Namapemohon',
            'NOKPPEMOHON' => 'Nokppemohon',
            'ALAMAT1' => 'Alamat1',
            'ALAMAT2' => 'Alamat2',
            'ALAMAT3' => 'Alamat3',
            'POSKOD' => 'Poskod',
            'NOTEL' => 'Notel',
            'NOSEWA' => 'Nosewa',
            'NOPETAK' => 'Nopetak',
            'LOKASIPETAK' => 'Lokasi',
            'JENISSEWA' => 'Jenissewa',
            'NAMAPENERIMA' => 'Namapenerima',
            'NOKPPENERIMA' => 'Nokppenerima',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
            'KETERANGAN_KATEGORI' => 'Keterangan Kategori',
            'KETERANGAN_KUMPULAN' => 'Keterangan Kumpulan',
        ];
    }
}
