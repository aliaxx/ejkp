<?php

namespace backend\modules\penyelenggaraan\modules\penggredan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\Perkara;

/**
 * PerkaraSearch represents the model behind the search form about `backend\modules\parameter\models\Perkara`.
 */
class PerkaraSearch extends Perkara
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['STATUS', 'JENIS'], 'integer'],
            [['JENIS', 'PRGN', 'KODPERKARA'], 'safe'],
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
        $query = Perkara::find()->joinWith(['updatedByUser']); //paramHeader rujuk dekat ParamHeader.php
      // $query = Perkara::find();

        $sort = [
            'attributes' => [
                'KODPERKARA',
                'PRGN',
                'STATUS',
                'JENIS' ,
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['JENIS' => SORT_ASC, 'KODPERKARA' => SORT_ASC],
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

        // grid/table at Perkara index page search 
       $query->andFilterWhere([
            'JENIS' => $this->JENIS,
            '{{%PP_PERKARA}}.STATUS' => $this->STATUS,
        ]);

        //query ini hanya yang display di halaman Perkara
        $query->andFilterWhere(['like', 'LOWER(KODPERKARA)', strtolower($this->KODPERKARA)],true)
              ->andFilterWhere(['like', 'LOWER(PRGN)', strtolower($this->PRGN)],true)
              ->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNAKHIR)],true);
        return $dataProvider;
    }
}
