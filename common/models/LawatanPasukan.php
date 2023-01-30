<?php

// namespace backend\modules\peniaga\models;
namespace common\models;

use common\models\Pengguna;

use Yii;

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
}
