<?php

namespace backend\modules\vektor\models;

use Yii;
use backend\modules\penyelenggaraan\models\ParamDetail;


/**
 * This is the model class for table "{{%PENGUATKUASAAN_BEKASPTP}}".
 *
 * @property int|null $ID
 * @property string|null $NOSIRI
 * @property string|null $NOSAMPEL No. Daftar Sampel
 * @property string|null $JENISBEKAS Jenis Bekas
 * @property int|null $BILBEKAS Bil. Bekas Diperiksa
 * @property int|null $BILPOTENSI Bil. Bekas Potensi Pembiakan (Bertakung Air)
 * @property int|null $BILPOSITIF Bil. Bekas Positif Pembiakan
 * @property string|null $KEPUTUSAN Keputusan Pembiakan (Spesis Nyamuk)
 * @property int|null $PURPA Positif Purpa
 * @property int|null $KAWASAN Kawasan Pembiakan
 * @property int|null $PGNDAFTAR Pengguna Daftar
 * @property int|null $TRKHDAFTAR Tarikh Daftar
 * @property int|null $PGNAKHIR Pengguna Akhir
 * @property int|null $TRKHAKHIR Tarikh Akhir
 */
class PenguatkuasaanBekasPtp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PENGUATKUASAAN_BEKASPTP}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'BILBEKAS', 'BILPOTENSI', 'BILPOSITIF', 'PURPA', 'KAWASAN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'safe'],
            [['NOSIRI', 'NOSAMPEL', 'JENISBEKAS', 'KEPUTUSAN'], 'string', 'max' => 20],
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
            'NOSIRI' => 'Nosiri',
            'NOSAMPEL' => 'No. Daftar Sampel',
            'JENISBEKAS' => 'Jenis Bekas',
            'BILBEKAS' => 'Bil. Bekas Diperiksa',
            'BILPOTENSI' => 'Bil. Bekas Potensi Pembiakan (Bertakung Air)',
            'BILPOSITIF' => 'Bil. Bekas Positif Pembiakan',
            'KEPUTUSAN' => 'Keputusan Pembiakan (Spesis Nyamuk)',
            'PURPA' => 'Positif Purpa',
            'KAWASAN' => 'Kawasan Pembiakan',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
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
     * @return yii\db\ActiveQuery
     */
    public function getJenisPembiakan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'KEPUTUSAN'])
        ->onCondition(['KODJENIS' => '27']);    
    }

    /**
     * @return yii\db\ActiveQuery
     */
    public function getLiputan()
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'KAWASAN'])
        ->onCondition(['KODJENIS' => '25']);    
    }

        /**
     * @return yii\db\ActiveQuery
     */
    public function getPenguatkuasaan()
    {
        return $this->hasOne(PenguatkuasaanPtp::className(), ['NOSIRI' => 'NOSIRI', 'NOSAMPEL' => 'NOSAMPEL']);
    }


}
