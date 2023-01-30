<?php

namespace backend\modules\penyelenggaraan\modules\perundangan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\Akta;
use backend\modules\penyelenggaraan\models\SeksyenKesalahan;

/**
 * SeksyenKesalahanSearch represents the model behind the search form about `backend\modules\parameter\models\SeksyenKesalahan`.
 */
class SeksyenKesalahanSearch extends SeksyenKesalahan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KODSALAH', 'STATUS'], 'integer'],
            [['KODAKTA', 'PRGN1','PRGN2','PRGNDENDA'], 'safe'],
            [['SEKSYEN','PRGNSEKSYEN'], 'safe'],
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
       $query = SeksyenKesalahan::find()->joinWith(['akta', 'updatedByUser']); //paramHeader rujuk dekat ParamHeader.php
    //    $query = SeksyenKesalahan::find();

        $sort = [ //for sorting
            'attributes' => [
                'KODAKTA' => [
                    'asc' => ['{{%AKTA}}.PRGN' => SORT_ASC],
                    'desc' => ['{{%AKTA}}.PRGN' => SORT_DESC],
                ],
                'KODSALAH',
                'SEKSYEN',
                'PRGNSEKSYEN',
                'PRGN1',
                'PRGN2',
                'PRGNDENDA',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
          //  'defaultOrder' => ['TRKHAKHIR' => SORT_ASC], //orderBy
          //'defaultOrder' => ['KODJENIS' => SORT_ASC, 'KODDETAIL' => SORT_ASC],
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

        // grid/table at SeksyenKesalahan index page search 
       $query->andFilterWhere([
        // var_dump("jfdfdn");
        // exit();
            'KODSALAH' => $this->KODSALAH,
            'STATUS' => $this->STATUS,
            

        ]);

        //query ini hanya yang display di halaman SeksyenKesalahan
        $query->andFilterWhere(['like', '{{%AKTA}}.KODAKTA', $this->KODAKTA])
        ->andFilterWhere(['like', 'KODSALAH', $this->KODSALAH])
        ->andFilterWhere(['like', 'SEKSYEN', $this->SEKSYEN])
        ->andFilterWhere(['like', 'PRGNSEKSYEN', $this->PRGNSEKSYEN])
        ->andFilterWhere(['like', 'PRGN1', $this->PRGN1])
        ->andFilterWhere(['like', 'PRGN2', $this->PRGN2])
        ->andFilterWhere(['like', 'PRGNDENDA', $this->PRGNDENDA])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);
        return $dataProvider;
    }
}
