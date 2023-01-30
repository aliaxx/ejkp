<?php

namespace backend\modules\lawatanmain\models;

use common\models\Pengguna;

use Yii;
use common\models\Pruser;

/**
 * This is the model class for table "{{%LAWATAN_PASUKAN}}".
 *
 * @property float $ID
 * @property string $IDMODULE
 * @property string $NOSIRI
 * @property int $IDPENGGUNA
 * @property int|null $JENISPENGGUNA
 * @property int|null $TURUTAN
 * @property string|null $CATATAN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKHDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class LawatanPasukan extends \yii\db\ActiveRecord
{
    const STATUS_AHLI = 2;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%LAWATAN_PASUKAN}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['IDMODULE', 'NOSIRI', 'IDPENGGUNA'], 'required'],
            [['IDPENGGUNA', 'JENISPENGGUNA', 'TURUTAN', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['IDMODULE'], 'string', 'max' => 3],
            [['NOSIRI'], 'string', 'max' => 20],
            [['CATATAN'], 'string', 'max' => 100],
            [['IDMODULE', 'NOSIRI', 'IDPENGGUNA'], 'unique', 'targetAttribute' => ['IDMODULE', 'NOSIRI', 'IDPENGGUNA']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'IDMODULE' => 'ID Module',
            'NOSIRI' => 'No Siri Rekod',
            'IDPENGGUNA' => 'ID Pengguna',
            'JENISPENGGUNA' => 'Jenis Pengguna',
            'TURUTAN' => 'Turutan',
            'CATATAN' => 'Catatan',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'Pengguna Akhir',
            'TRKHAKHIR' => 'Tarikh Akhir',
        ];
    }

    
    public function getPengguna0()
    {
        return $this->hasOne(Pengguna::className(), ['ID' => 'IDPENGGUNA']);
    }
    
    public function getJawatan() //nor23112022
    {
        // return $this->hasOne(Pruser::className(), ['USERID' => 'IDPENGGUNA']);
        // $get_data = Yii::$app->db->createCommand(" SELECT * FROM C##MAJLIS.PRUSER 
        // WHERE 'USERID' = '$this->IDPENGGUNA'")->queryOne();

        $sql = "Select DESIGNATION from C##MAJLIS.PRUSER where USERID  =  '$this->IDPENGGUNA'";
        $get_data = \Yii::$app->db->createCommand($sql)->queryScalar();

        // $get_datas = $get_data[0]['DESIGNATION'];
        return $get_data;
    }

}
