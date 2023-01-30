<?php

namespace backend\modules\penyelenggaraan\modules\param\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\ParamDetail;

/**
 * ParamDetailSearch represents the model behind the search form about `backend\modules\parameter\models\ParamDetail`.
 */
class ParamDetailSearch extends ParamDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KODDETAIL', 'STATUS'], 'integer'],
            [['KODJENIS', 'PRGN'], 'safe'],
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
       $query = ParamDetail::find()->joinWith(['paramHeader', 'updatedByUser']); //paramHeader rujuk dekat ParamHeader.php
    //    $query = ParamDetail::find();

        $sort = [ //for sorting
            'attributes' => [
                'KODDETAIL',
                // '{{%PARAMETER_DETAIL}}.PRGN',
                'PRGN' => [
                    'asc' => ['{{%PARAMETER_DETAIL}}.PRGN' => SORT_ASC],
                    'desc' => ['{{%PARAMETER_DETAIL}}.PRGN' => SORT_DESC],
                ],
                'STATUS',
                'KODJENIS' => [
                    'asc' => ['{{%PARAMETER_HEADER}}.PRGN' => SORT_ASC],
                    'desc' => ['{{%PARAMETER_HEADER}}.PRGN' => SORT_DESC],
                ],
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
          //  'defaultOrder' => ['TRKHAKHIR' => SORT_ASC], //orderBy
          'defaultOrder' => ['KODJENIS' => SORT_ASC, 'KODDETAIL' => SORT_ASC],
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

        // grid/table at ParamDetail index page search 
       $query->andFilterWhere([
        // var_dump("jfdfdn");
        // exit();
            'KODDETAIL' => $this->KODDETAIL,
            '{{%PARAMETER_DETAIL}}.STATUS' => $this->STATUS,
            // 'paramHeader.KODJENIS' => $this->KODJENIS,
            '{{%PARAMETER_DETAIL}}.KODJENIS' => $this->KODJENIS,

        ]);

        //query ini hanya yang display di halaman paramdetail
        $query->andFilterWhere(['like', '{{%PARAMETER_DETAIL}}.PRGN', $this->PRGN])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);
        return $dataProvider;
    }
}
