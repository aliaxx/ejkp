<?php

namespace backend\modules\penyelenggaraan\modules\lokaliti\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\Lokaliti;

/**
 * LokalitiSearch represents the model behind the search form of `backend\modules\penyelenggaraan\models\Lokaliti`.
 */
class LokalitiSearch extends Lokaliti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['IDMUKIM', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'IDZONAM'], 'integer'],
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
        $query = Lokaliti::find();

        // add conditions that should always apply here
        $sort = [
            'attributes' => [
                'ID',
                'IDMUKIM',
                'PRGN',
                'STATUS',
                'PGNDAFTAR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['IDMUKIM' => SORT_ASC],
        ];        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
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
            'IDMUKIM' => $this->IDMUKIM,
            'STATUS' => $this->STATUS,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
            'IDZONAM' => $this->IDZONAM,
        ]);

        $query->andFilterWhere(['like', 'PRGN', $this->PRGN]);

        return $dataProvider;
    }
}
