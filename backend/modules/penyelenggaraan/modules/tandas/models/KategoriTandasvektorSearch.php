<?php

namespace backend\modules\penyelenggaraan\modules\tandas\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\KategoriTandasvektor;

/**
 * KategoriTandasvektorSearch represents the model behind the search form of `backend\modules\penyelenggaraan\models\KategoriTandasvektor`.
 */
class KategoriTandasvektorSearch extends KategoriTandasvektor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['PRGN'], 'safe'],
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
        $query = KategoriTandasvektor::find();

        // add conditions that should always apply here

        $sort = [
            'attributes' => [
                'ID',
                'PRGN',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['ID' => SORT_ASC],
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
            'ID' => $this->ID,
            'STATUS' => $this->STATUS,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'PRGN', $this->PRGN]);

        return $dataProvider;
    }
}
