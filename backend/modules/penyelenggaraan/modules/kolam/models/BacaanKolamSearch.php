<?php

namespace backend\modules\penyelenggaraan\modules\kolam\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\BacaanKolam;

/**
 * BacaanKolamSearch represents the model behind the search form of `backend\modules\penyelenggaraan\models\BacaanKolam`.
 */
class BacaanKolamSearch extends BacaanKolam
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'STATUS'], 'integer'],
            [['PGNDAFTAR', 'PGNAKHIR'], 'safe'],
            [['NAMAPARAM', 'NILAIPIAWAI', 'UNIT'], 'safe'],
            // [['TRKHDAFTAR', 'TRKHAKHIR'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = BacaanKolam::find()->joinWith('updatedByUser');

        // add conditions that should always apply here
        $sort = [
            'attributes' => [
                'ID',
                'NAMAPARAM',
                'NILAIPIAWAI',
                'UNIT',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                // 'TRKHAKHIR',
            ],
            'defaultOrder' => ['PRGN' => SORT_ASC],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            '{{%PK_BACAANKOLAM}}.STATUS' => $this->STATUS,
            // 'PGNDAFTAR' => $this->PGNDAFTAR,
            // 'TRKHDAFTAR' => $this->TRKHDAFTAR,
            // 'PGNAKHIR' => $this->PGNAKHIR,
            // 'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'LOWER(NAMAPARAM)', strtolower($this->NAMAPARAM)],true)
            ->andFilterWhere(['like', 'NILAIPIAWAI', $this->NILAIPIAWAI])
            ->andFilterWhere(['like', 'UNIT', $this->UNIT])
            ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);

        return $dataProvider;
    }
}
