<?php

namespace backend\modules\vektor\models;
use backend\modules\penyelenggaraan\models\ParamDetail;
use backend\modules\penyelenggaraan\models\Perkara;
use backend\modules\penyelenggaraan\models\PerkaraKomponen;
use backend\modules\penyelenggaraan\models\PerkaraKomponenPrgn;
use backend\modules\lawatanmain\models\LawatanMain; //alia 14122022
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
class Transtandas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $PERKARAPRGN, $KOMPONENPRGN, $PRGN, $jumlahmarkah, $gred,$CHKITEM, $checked, $jenistandas, $total;
    // , $JUM_MARKAH;
    public static function tableName()
    {
        return '{{%GRED_TANDAS}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN','KATPREMIS'], 'required'],
            [['MARKAH','ML','MW','MO','MU', 'MK', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['IDMODULE', 'KODKOMPONEN'], 'string', 'max' => 2],
            [['NOSIRI'], 'string', 'max' => 20],
            [['KODPERKARA'], 'string', 'max' => 1],
            [['KODPRGN'], 'string', 'max' => 4],
            [['CATATAN'], 'string', 'max' => 100],
            [['DEMERIT'], 'string', 'max' => 45],
            [['KATPREMIS'], 'safe'],
            [['NOSIRI', 'KODPERKARA', 'KODKOMPONEN'], 'unique', 'targetAttribute' => ['NOSIRI', 'KODPERKARA', 'KODKOMPONEN']],
            [['CHKITEM', 'checked'], 'safe'], //ZIHAN 20221021
            [['JUM_MARKAH'], 'integer'],
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
            'ML' => 'Markah',
            'MW' => 'Markah',
            'MO' => 'Markah',
            'MU' => 'Markah',
            'MK' => 'Markah',
            'CATATAN' => 'Catatan',
            'DEMERIT' => 'Demerit',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pgndaftar',
            'TRKHDAFTAR' => 'Trkhdaftar',
            'PGNAKHIR' => 'Pgnakhir',
            'TRKHAKHIR' => 'Trkhakhir',
            'KATPREMIS' => 'Kategori Premis',
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

    // public function getKatpremis0()
    // {

    //     return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'KATPREMIS'])
    //         ->onCondition(['KODJENIS' => 1]);
    // }
    public function getPemilik0()
    {

        return $this->hasOne(LawatanPemilik::className(), ['NOSIRI' => 'NOSIRI']);
    }

    public function getTranstandasrec()
    {
            // var_dump($this);
            // exit();
        return $this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID');  

    }

    public function getMarkahtandas()
    {
        
        $sum=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('ML');
        $sum1=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MW');
        $sum2=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MO');
        $sum3=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MU');
        $sum4=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MK');

        // $sum=$this->hasOne($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('ML');
        // $sum1=$this->hasOne($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MW');
        // $sum2=$this->hasOne($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MO');
        // $sum3=$this->hasOne($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MU');
        // $sum4=$this->hasOne($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('MK');

        // $sumtotal=$this->hasMany($this, ['NOSIRI' => 'NOSIRI'])->orderBy('ID')->sum('ML','MW','MO','MU','MK');
        
        // $sum=100-$sum;
        $total =$sum+$sum1+$sum2+$sum3+$sum4;

        // var_dump($total);
        // exit;
        if(($total>=91) && ($total<=100)){
            $gred='* * * * *';
        }else if(($total>=81) && ($total<=90)){
            $gred='* * * *';
        }else if(($total>=71) && ($total<=80)){
            $gred='* * *';
        }else if(($total>=61) && ($total<=70)){
            $gred='* *';
        }else if(($total>=51) && ($total<=60)){
            $gred='*';
        }else{
            $gred='TIADA BINTANG (NOTIS, KOMPAUN DIBERIKAN)';
        }

        $data = [];
        $data['sum']=$sum;
        $data['sum1']=$sum1;
        $data['sum2']=$sum2;
        $data['sum3']=$sum3;
        $data['sum4']=$sum4;
        $data['total']=$total; //total semua
        $data['gred']=$gred;
        // $data['totalrow']=$row;
        // $data['sumtotal']=$sumtotal;
        // var_dum p($data);
        // var_dump($sumtotal);
        // exit()      ;
// exit();
        return $data;
    }   

    public function getPerkara0() 
    {
        return $this->hasOne(Perkara::className(), ['KODPERKARA' => 'KODPERKARA'])
        ->onCondition(['JENIS' => 2]);
    }
    public function getKomponen0() 
    {
        return $this->hasOne(PerkaraKomponen::className(), ['KODPERKARA' => 'KODPERKARA', 'KODKOMPONEN' => 'KODKOMPONEN']);
    }
    public function getPrgn0() 
    {
        return $this->hasOne(PerkaraKomponenPrgn::className(), ['KODPERKARA' => 'KODPERKARA', 'KODKOMPONEN' => 'KODKOMPONEN', 'KODPRGN' => 'KODPRGN']);
    }

    public function getKatpremis0() 
    {
        return $this->hasOne(ParamDetail::className(), ['KODDETAIL' => 'KATPREMIS'])
        ->onCondition(['KODJENIS' => 1]);
    }

    public function getPremis0() 
    {
        return $this->hasOne(LawatanMain::className(), ['NOSIRI' => 'NOSIRI']);
    }
    
}
