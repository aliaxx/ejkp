<?php

namespace backend\modules\penyelenggaraan\models;

use Yii;

/**
 * This is the model class for table "TBAKTA".
 *
 * @property string $KODAKTA
 * @property string|null $PRGN
 * @property int|null $STATUS
 * @property int|null $PGNDAFTAR
 * @property int|null $TRKDAFTAR
 * @property int|null $PGNAKHIR
 * @property int|null $TRKHAKHIR
 */
class Akta extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%AKTA}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            \common\behaviors\TimestampBehavior::className(),
            \common\behaviors\BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['KODAKTA'], 'required'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['KODAKTA'], 'string', 'max' => 20],
            [['PRGN'], 'string', 'max' => 255],
            [['KODAKTA'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'KODAKTA' => 'Kod Akta',
            'PRGN' => 'Keterangan',
            'STATUS' => 'Status',
            'STATUS' => 'Status',
            'PGNDAFTAR' => 'ID Pengguna Daftar',
            'TRKHDAFTAR' => 'Tarikh Daftar',
            'PGNAKHIR' => 'ID Pengguna Kemaskini Terakhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
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
}
