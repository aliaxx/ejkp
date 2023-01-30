<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%tetapan_sesi}}".
 *
 * @property string $code
 * @property string $value
 * @property int $updated_at
 * @property int $updated_by
 */
class TetapanSesi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tetapan_sesi}}';
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
            [['jenis', 'jangka_masa'], 'required'],
            [['jangka_masa', 'pgndaftar', 'trkhdaftar', 'pgnakhir', 'trkhakhir'], 'integer'],
            [['jenis'], 'string', 'max' => 255],
            [['jangka_masa'], 'integer', 'min' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'jenis' => Yii::t('app', 'Jenis Tetapan'),
            'jangka_masa' => Yii::t('app', 'Jangka Masa Sesi (Minit)'),
            'pgndaftar' => Yii::t('app', 'Pengguna Daftar'),
            'trkhdaftar' => Yii::t('app', 'Tarikh Daftar'),
            'pgnakhir' => Yii::t('app', 'Pengguna Akhir'),
            'trkhakhir' => Yii::t('app', 'Tarikh Akhir'),
        ];
    }
}
