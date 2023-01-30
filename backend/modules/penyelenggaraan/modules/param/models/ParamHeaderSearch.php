<?php

namespace backend\modules\penyelenggaraan\modules\param\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\penyelenggaraan\models\ParamHeader;

/**
 * ParamHeaderSearch represents the model behind the search form about `backend\modules\parameter\models\ParamHeader`.
 */
class ParamHeaderSearch extends ParamHeader
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KODJENIS', 'STATUS'], 'integer'],
            [['PRGN'], 'safe'],
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
        $query = ParamHeader::find()->joinWith('updatedByUser');

        $sort = [
            'attributes' => [
                'KODJENIS',
                'PRGN',
                'STATUS',
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['KODJENIS' => SORT_ASC],
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

        $query ->andFilterWhere(['like', 'KODJENIS', $this->KODJENIS])
               ->andFilterWhere(['like', 'PRGN', $this->PRGN])
               ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR])
               ->andFilterWhere(['{{%PARAMETER_HEADER}}.STATUS' => $this->STATUS]);

        return $dataProvider;
    }
}
