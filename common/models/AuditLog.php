<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%audit_log}}".
 *
 * @property int $id
 * @property int $tindakan
 * @property string $namatable
 * @property string $urlmenu
 * @property string $datalama
 * @property string $data
 * @property int $pengguna
 * @property datetime $tarikhmasa
 */
class AuditLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%AUDIT_LOG}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TINDAKAN', 'PENGGUNA', 'TARIKHMASA'], 'integer'],
            [['NAMATABLE', 'URLMENU'], 'string', 'max' => 255],
            [['DATALAMA', 'DATA'], 'string', 'max' => 3000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'TINDAKAN' => 'Tindakan Pengguna',
            'NAMATABLE' => 'Nama Table',
            'URLMENU' => 'Url Menu',
            'DATALAMA' => 'Data Lama',
            'DATA' => 'Data Baru',
            'PENGGUNA' => 'Pengguna',
            'TARIKHMASA' => 'Tarikh & Masa',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['ID' => 'PENGGUNA']);
    }
}
