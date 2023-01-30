<?php

namespace backend\modules\vektor\models;

use Yii;

/**
 * This is the model class for table "{{%BEKAS_PTP}}".
 *
 * @property float $ID
 * @property string $NOSIRI
 * @property int|null $KAWASAN
 * @property string|null $JENISBEKAS
 * @property int|null $BILBEKAS
 * @property int|null $BILPOTENSI
 * @property int|null $BILPOSITIF
 * @property string|null $KEPUTUSAN
 * @property string|null $PURPA
 * @property string|null $CATATAN
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class BekasLvc extends \yii\db\ActiveRecord
{
    public $ai, $bi, $ci, $liputan, $hpi;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%BEKAS_LVC}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'NOSIRI', 'KAWASAN'], 'required'],
            [['ID'], 'number'],
            [['KAWASAN', 'BILBEKAS', 'BILPOTENSI', 'BILPOSITIF', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI'], 'string', 'max' => 20],
            [['ai', 'bi', 'ci', 'hpi'], 'safe'],
            [['JENISBEKAS', 'KEPUTUSAN', 'PURPA', 'CATATAN'], 'string', 'max' => 250],
            [['ID', 'NOSIRI'], 'unique', 'targetAttribute' => ['ID', 'NOSIRI']],
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
            'KAWASAN' => 'Kawasan Pembiakan',
            'JENISBEKAS' => 'Jenis Bekas',
            'BILBEKAS' => 'Bil Bekas',
            'BILPOTENSI' => 'Bil Bekas Potensi Pembiakan (Bertakung Air)',
            'BILPOSITIF' => 'Bil. Bekas Positif Pembiakan',
            'KEPUTUSAN' => 'Keputusan Pembiakan (Spesis Nyamuk)',
            'PURPA' => 'Positif Purpa',
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
     * @return yii\db\ActiveQuery
     */
    public function getPremis()
    {
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI']);  

    }

    public function getPencapaian()
    {
        $bekas = BekasLvc::findOne(['NOSIRI' => $this->NOSIRI]);

        $bekas->ai = Yii::$app->db->createCommand("SELECT (SUM(JUMPOSITIF)/SUM(PENCAPAIAN))FROM TBSASARAN_LVC 
        WHERE NOSIRI = '$bekas->NOSIRI'")->queryOne();
        $this->ai = implode(' ', $bekas->ai); //convert to string
        // $ai1 = floor(($ai*100))/100; //maintain decimal without rounding but will ignore the last number if 0. eg: 0.20=>0.2  
        // $ai1 = bcdiv($ai, 1, 2) //truncated value to 2 decimal places
        // var_dump($this->ai);
        // exit();


        $a = $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('BILPOSITIF');
        $b = $this->hasMany(SasaranLvc::className(), ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('PENCAPAIAN');
        if($b){ //nor02122022
            $this->bi = $a/$b;
        }else{
            return null;
        }

        $bekas->ci = Yii::$app->db->createCommand("SELECT (SUM(BILPOSITIF)/SUM(BILBEKAS)) FROM TBBEKAS_LVC 
                WHERE NOSIRI = '$bekas->NOSIRI'")->queryOne();
        $this->ci = implode(' ', $bekas->ci); 
        // $ci1 = floor(($ci*100))/100;  
        
        $bilbekas1 = Yii::$app->db->createCommand("SELECT SUM(BILBEKAS) FROM TBBEKAS_LVC WHERE NOSIRI = '$bekas->NOSIRI' AND KAWASAN = 1")->queryScalar();
        $bilpotensi1 = Yii::$app->db->createCommand("SELECT SUM(BILPOTENSI) FROM TBBEKAS_LVC WHERE NOSIRI = '$bekas->NOSIRI' AND KAWASAN = 1")->queryScalar();
        // $bekas = implode(' ', $bilbekas1);   

        $bilbekas2 = Yii::$app->db->createCommand("SELECT SUM(BILBEKAS) FROM TBBEKAS_LVC WHERE NOSIRI = '$bekas->NOSIRI' AND KAWASAN = 2")->queryScalar();
        $bilpotensi2 = Yii::$app->db->createCommand("SELECT SUM(BILPOTENSI) FROM TBBEKAS_LVC WHERE NOSIRI = '$bekas->NOSIRI' AND KAWASAN = 2")->queryScalar();
        // $bilbekas = implode(' ', $bilbekas1);   


        $data = [];
        $data['ai']=$this->ai;
        $data['bi']=$this->bi;
        $data['ci']=$this->ci;
        $data['bilbekas1']=$bilbekas1;
        $data['bilpotensi1']=$bilpotensi1;
        $data['bilbekas2']=$bilbekas2;
        $data['bilpotensi2']=$bilpotensi2;


        return $data;
    }   




}
