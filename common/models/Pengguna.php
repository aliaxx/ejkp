<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%PENGGUNA}}".
 *
 * @property int $ID
 * @property string|null $AUTH_KEY
 * @property string $NOKP
 * @property string|null $KODCAWANGAN
 * @property string $NAMA
 * @property string|null $JANTINA
 * @property string|null $NAMA_BAHAGIAN
 * @property string|null $NAMA_GELARAN
 * @property string|null $NAMA_GELARAN_JAWATAN
 * @property string|null $NAMA_JAWATAN
 * @property string|null $GRED
 * @property int|null $IDGRED
 * @property string|null $NO_TELEFON
 * @property string|null $NO_TEL_BIMBIT
 * @property string|null $EMEL
 * @property int|null $SUSUNAN
 * @property string|null $GAMBAR
 * @property string|null $AKTIF_STAF
 * @property int|null $IDBAHAGIAN
 * @property string|null $IDGRED_KUMPULAN
 * @property string|null $IDSKIM
 * @property string|null $IDTARAF_JAWATAN
 * @property string|null $IDNEGERI_BAHAGIAN
 * @property string|null $ALAMAT
 * @property string|null $POSKOD
 * @property string|null $TARIKH_LAHIR
 * @property string|null $BANDAR
 * @property string|null $SEKSYEN
 * @property string|null $UNIT
 * @property string|null $SINGKATAN_JAWATAN
 * @property string|null $PENGGUNA_APPS
 * @property int|null $DATA_FILTER
 * @property int $PERANAN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 * @property int|null $IDNEGERI
 */
class Pengguna extends \yii\db\ActiveRecord
{

    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PENGGUNA}}';
    }

        /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'NOKP', 'NAMA', 'PERANAN','SUBUNIT','DATA_FILTER'], 'required'],
            [['ID', 'DATA_FILTER', 'PERANAN', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOKP'], 'string', 'max' => 12],
            [['SUBUNIT', 'CUSTOMERID'], 'string', 'max' => 20],
            [['NAMA'], 'string', 'max' => 300],
            [['AUTH_KEY'], 'string', 'max' => 32],
            [['EMAIL', 'USERNAME'], 'string', 'max' => 100],
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
            'NOKP' => 'No. Kad Pengenalan',
            'SUBUNIT' => 'Subunit',
            'NAMA' => 'Nama',
            'DATA_FILTER' => 'Data Filter',
            'PERANAN' => 'Peranan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
            'AUTH_KEY' => 'Auth Key',
            'CUSTOMERID' => 'ID Staf',
            'EMAIL' => 'Email',
            'USERNAME' => 'ID Pengguna',
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
    public function getPeranan0()
    {
        return $this->hasOne(\common\models\Peranan::className(), ['IDPERANAN' => 'PERANAN']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\Pruser::className(), ['USERID' => 'ID']);
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    //to get subunit
    public function getSubunit0()
    {
         return $this->hasOne(\backend\modules\penyelenggaraan\models\Subunit::className(), ['ID' => 'SUBUNIT']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(\backend\modules\penyelenggaraan\models\ParamDetail::className(), ['KODDETAIL' => 'ID_KODUNIT'])
            ->onCondition(['KODJENIS' => '23'])
            ->viaTable('{{%SUBUNIT}}', ['ID' => 'SUBUNIT']);
    }

    
}
