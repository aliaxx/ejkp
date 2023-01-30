<?php

namespace backend\modules\premis\models;
use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\lawatanmain\models\LawatanPemilik;

use Yii;

/**
 * This is the model class for table "TBANUGERAH".
 *
 * @property float $ID
 * @property string|null $IDMODULE
 * @property string $NOSIRI
 * @property int $NOLESEN
 * @property string|null $NOSSM
 * @property string|null $TAHUN
 * @property string|null $GRED
 * @property string|null $CATATAN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Anugerah extends \yii\db\ActiveRecord
{
    public $NAMASYARIKAT, $NAMAPREMIS;      //$NOLESEN, $NOSSM, 
    public $ALAMAT1, $ALAMAT2, $ALAMAT3, $POSKOD;
    public $prgnidmodule;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ANUGERAH}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['IDMODULE'], 'string', 'max' => 3],
            [['NOSIRI'], 'string', 'max' => 20],
            // [['NOLESEN'], 'string', 'max' => 50],
            // [['TAHUN', 'GRED', 'CATATAN'], 'string', 'max' => 45],
            [['prgnidmodule', 'NOLESEN', 'NOSSM', 'NAMASYARIKAT', 'NAMAPREMIS','TAHUN', 'GRED', 'CATATAN'], 'safe'],
            [['NOSIRI'], 'unique', 'targetAttribute' => ['NOSIRI']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IDMODULE' => 'Idmodule',
            'NOSIRI' => 'No Siri Rekod',
            'NOLESEN' => 'No Lesen',
            'NOSSM' => 'No Syarikat',
            'TAHUN' => 'Tahun',
            'GRED' => 'Gred',
            'CATATAN' => 'Catatan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
        ];
    }
    public function setNosiri($idmodule)
    {
        $curYear = date('y'); 
        $query = Anugerah::find()->where(['IDMODULE' =>$idmodule])->orderBy(['NOSIRI' => SORT_DESC])->one();
        // var_dump($query);
        if (isset($query)) {
            $siri = substr($query->NOSIRI, 8); //4 stand for  bilangan of (Prefix)(Current year). PT22
            // var_dump($siri);
            $siriid = (int)$siri + 1; 
            
            $siriid = sprintf("%05s", $siriid);
            // var_dump($siriid);
            // var_dump("if");
        } else {
            $siriid = '00001';
            // var_dump("else");
        }
        
        $nosiri = $idmodule .$curYear. $siriid;
        $this->NOSIRI = $nosiri; //output

        // var_dump($this->NOSIRI);
        // exit;
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
}
