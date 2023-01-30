<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Peranan;

/**
 * PerananSearch represents the model behind the search form about `common\models\Peranan`.
 */
class PerananSearch extends Peranan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDPERANAN', 'STATUS'], 'integer'],
            [['NAMAPERANAN'], 'safe'],
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
        $query = Peranan::find()->joinWith('updatedByUser');

        $sort = [
            'attributes' => [
                'IDPERANAN',
                'NAMAPERANAN',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
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
            'IDPERANAN' => $this->IDPERANAN,
            '{{%PERANAN}}.STATUS' => $this->STATUS,
        ]);

        $query ->andFilterWhere(['like', 'LOWER(NAMAPERANAN)', strtolower($this->NAMAPERANAN)],true)
               ->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNAKHIR)],true);

        return $dataProvider;
    }
}
