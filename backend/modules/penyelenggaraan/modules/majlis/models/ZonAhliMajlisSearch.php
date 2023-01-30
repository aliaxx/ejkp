<?php

namespace backend\modules\penyelenggaraan\modules\majlis\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \common\models\Pengguna;
use backend\modules\penyelenggaraan\models\ZonAhliMajlis;

/**
 * ZonAhliMajlisSearch represents the model behind the search form about `backend\modules\parameter\models\ZonAhliMajlis`.
 */
class ZonAhliMajlisSearch extends ZonAhliMajlis
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'STATUS'], 'integer'],
            [['NAMAAHLIMAJLIS'], 'safe'],
            [['PRGNZON'], 'safe'],
            [['PENGGAL'], 'safe'],
            [['PRGNPANJANG'], 'safe'],
            [['PGNAKHIR'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ZonAhliMajlis::find()->joinWith('updatedByUser');

        $sort = [
            'attributes' => [
                'PRGNZON',
                'PRGNPANJANG',
                'NAMAAHLIMAJLIS',
                'PENGGAL',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['PRGNZON' => SORT_ASC],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => ['pageSizeLimit' => [1, 200]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'PENGGAL' => $this->PENGGAL,
            '{{TBZON_AHLIMAJLIS}}.STATUS' => $this->STATUS,
        ]);

        //walaupun search guna satu huruf, data still akan display melainkan huruf tersebut tiada data. 
        //Contoh Nama Ahli Majlis : EN. ANWAR BIN NEKHAN. Search 'ANWAR' shj, ia akan display. 
        $query->andFilterWhere(['like', 'PRGNZON', $this->PRGNZON])
              ->andFilterWhere(['like', 'PRGNPANJANG', $this->PRGNPANJANG])
              ->andFilterWhere(['like', 'NAMAAHLIMAJLIS', $this->NAMAAHLIMAJLIS])
              ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);

        return $dataProvider;
    }
}
