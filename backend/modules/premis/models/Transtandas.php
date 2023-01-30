<?php

namespace backend\modules\premis\models;
use backend\modules\penyelenggaraan\models\ParamDetail;
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
    public $KATPREMIS,$PERKARAPRGN, $KOMPONENPRGN, $PRGN, $jumlahmarkah, $gred;
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
        // var_dump($sum);
        // exit();
        // $sum=100-$sum;
        $total =$sum+$sum1+$sum2+$sum3;
        if(($total>=91) && ($total<=100)){
            $gred='A';
        }else if(($total>=81) && ($total<=90)){
            $gred='B';
        }else if(($total>=71) && ($total<=80)){
            $gred='C';
        }else if(($total>=61) && ($total<=70)){
            $gred='D';
        }else if(($total>=51) && ($total<=60)){
            $gred='E';
        }else{
            $gred='F';
        }

        $data = [];
        $data['total']=$total;
        $data['gred']=$gred;
//         var_dump($data);
// exit();
        return $data;
    }   
    
}
