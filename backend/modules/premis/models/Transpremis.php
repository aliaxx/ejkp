<?php

namespace backend\modules\premis\models;

use backend\modules\lawatanmain\models\LawatanMain;
use backend\modules\penyelenggaraan\models\Perkara;
use backend\modules\penyelenggaraan\models\PerkaraKomponen;
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;

use backend\modules\lawatanmain\models\LawatanPemilik;

use Yii;

/**
 * This is the model class for table "{{%GRED_PREMIS}}".
 *
 * @property float $ID
 * @property string|null $IDMODULE
 * @property string $NOSIRI
 * @property string $KODPERKARA
 * @property string $KODKOMPONEN
 * @property string $KODPRGN
 * @property int|null $MARKAH
 * @property string|null $CATATAN
 * @property string|null $DEMERIT
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Transpremis extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $PERKARAPRGN, $KOMPONENPRGN, $PRGN, $jumlahmarkah, $gred, $CHKITEM, $checked, $sum_demerit, $sum_totalmark;
    public static function tableName()
    {
        return '{{%GRED_PREMIS}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN'], 'required'],
            [['MARKAH', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['IDMODULE', 'KODKOMPONEN'], 'string', 'max' => 3],
            [['NOSIRI'], 'string', 'max' => 20],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['KODPRGN'], 'string', 'max' => 4],
            [['CATATAN'], 'string', 'max' => 100],
            [['DEMERIT'], 'string', 'max' => 45],
            [['NOSIRI', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN'], 'unique', 'targetAttribute' => ['NOSIRI', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN']],
            [['CHKITEM', 'checked'], 'safe'], //ZIHAN 20221021
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
            'NOSIRI' => 'Nosiri',
            'KODPERKARA' => 'Kodperkara',
            'KODKOMPONEN' => 'Kodkomponen',
            'KODPRGN' => 'Kodprgn',
            'MARKAH' => 'Markah',
            'CATATAN' => 'Catatan',
            'DEMERIT' => 'Demerit',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
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

    public function getTranspremisrec()
    {
            // var_dump($this);
            // exit();
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID');  

    }

    public function getMarkahpremis()
    {
        $sum_totalmark = $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MARKAH');
        $sum_demerit = $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('DEMERIT');

        //$sum=100-$sum;
        //$sum = round(($sum_demerit/$sum_totalmark) * 100);
        // $sum = round((($sum_totalmark - $sum_demerit) / $sum_totalmark) * 100);
        $sum = round(($sum_demerit/$sum_totalmark)*100);

        if(($sum>=86) && ($sum<=100)){
            $gred='A';
        }else if(($sum>=71) && ($sum<=85)){
            $gred='B';
        }else if(($sum>=51) && ($sum<=70)){
            $gred='C';
        }else{
            $gred='D';
        }

        $data = [];
        $data['sum']=$sum;
        $data['gred']=$gred;
        $data['sum_totalmark']=$sum_totalmark;
        $data['sum_demerit']=$sum_demerit;
        return $data;
    }  

    public function getPremis0() 
    {
        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getPemilik0() 
    {
        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI'])->via('premis0');
    }

    public function getPerkara0() 
    {
        return $this->hasOne(Perkara::className(), ['KODPERKARA' => 'KODPERKARA'])
        ->onCondition(['JENIS' => 1]);;
    }
    public function getKomponen0() 
    {
        return $this->hasOne(PerkaraKomponen::className(), ['KODPERKARA' => 'KODPERKARA', 'KODKOMPONEN' => 'KODKOMPONEN']);
    }
    public function getPrgn0() 
    {
        return $this->hasOne(PerkaraKomponenPrgn::className(), ['KODPERKARA' => 'KODPERKARA', 'KODKOMPONEN' => 'KODKOMPONEN', 'KODPRGN' => 'KODPRGN']);
    }
    
}
