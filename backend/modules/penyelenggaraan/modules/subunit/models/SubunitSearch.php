<?php

namespace backend\modules\penyelenggaraan\modules\subunit\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\Subunit;

/**
 * SubunitSearch represents the model behind the search form of `backend\modules\penyelenggaraan\models\Subunit`.
 */
class SubunitSearch extends Subunit
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['ID_KODUNIT', 'STATUS'], 'integer'],
            [['PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR'], 'safe'],
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
        $query = Subunit::find()->joinWith('updatedByUser');

        // add conditions that should always apply here
        $sort = [
            'attributes' => [
                'ID',
                'ID_KODUNIT',
                'PRGN',
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
            'ID_KODUNIT' => $this->ID_KODUNIT,
            '{{%DUN}}.STATUS' => $this->STATUS,
            // 'PGNDAFTAR' => $this->PGNDAFTAR,
            // 'TRKHDAFTAR' => $this->TRKHDAFTAR,
            // 'PGNAKHIR' => $this->PGNAKHIR,
            // 'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        //this function is to search without case sensetive --NOR180822
        $query->andFilterWhere(['like', 'LOWER(PRGN)', strtolower($this->PRGN)],true)
              ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);


        return $dataProvider;
    }
}
