<?php

namespace backend\modules\penyelenggaraan\modules\perundangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\Akta;

/**
 * AktaSearch represents the model behind the search form about `backend\modules\parameter\models\Akta`.
 */
class AktaSearch extends Akta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['STATUS'], 'integer'],
            [['KODAKTA','PRGN'], 'safe'],
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
        $query = Akta::find()->joinWith('updatedByUser');

        $sort = [
            'attributes' => [
                'KODAKTA',
                'PRGN',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['KODAKTA' => SORT_ASC],
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

        $query ->andFilterWhere(['like', 'LOWER(KODAKTA)', strtolower($this->KODAKTA)],true)
               ->andFilterWhere(['like', 'LOWER(PRGN)', strtolower($this->PRGN)],true)
               ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR])
               ->andFilterWhere(['{{%AKTA}}.STATUS' => $this->STATUS]);

        return $dataProvider;
    }
}
