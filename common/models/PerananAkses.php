<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%peranan_akses}}".
 *
 * @property int $IDPERANAN
 * @property string $KODAKSES
 * @property int $AKSES_LIHAT
 * @property int $AKSES_URUS
 * @property int $PGNAKHIR
 * @property int $TRKHAKHIR
 *
 * @property Peranan $peranan
 */
class PerananAkses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%PERANAN_AKSES}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['TRKHAKHIR'],
                ],
            ],
            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['PGNAKHIR'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['IDPERANAN', 'KODAKSES'], 'required'],
            [['IDPERANAN', 'AKSES_LIHAT', 'AKSES_URUS', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['KODAKSES'], 'string', 'max' => 255],
            [['IDPERANAN', 'KODAKSES'], 'unique', 'targetAttribute' => ['IDPERANAN', 'KODAKSES']],
            [['IDPERANAN'], 'exist', 'skipOnError' => true, 'targetClass' => Peranan::className(), 'targetAttribute' => ['IDPERANAN' => 'IDPERANAN']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'IDPERANAN' => 'ID Peranan',
            'KODAKSES' => 'Kod Akses',
            'AKSES_LIHAT' => 'Akses Lihat',
            'AKSES_URUS' => 'Akses Urus',
            'PGNAKHIR' => 'ID Pengguna Kemaskini Terakhir',
            'TRKHAKHIR' => 'Tarikh Kemaskini Terakhir',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeranan()
    {
        return $this->hasOne(Peranan::className(), ['IDPERANAN' => 'IDPERANAN']);
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
