<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\SasaranSrt;

/**
 * SasaranSrtSearch represents the model behind the search form of `backend\modules\vektor\models\SasaranSrt`.
 */
class SasaranSrtSearch extends SasaranSrt
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI'], 'safe'],
            [['ID_JENISPREMIS', 'JUMPREMIS', 'PENCAPAIAN1', 'PENCAPAIAN2', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
    public function search($params, $nosiri = null)
    {
        $query = SasaranSrt::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
                'ID',
                'ID_JENISPREMIS',
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
            'ID_JENISPREMIS' => $this->ID_JENISPREMIS,
            'JUMPREMIS' => $this->JUMPREMIS,
            'PENCAPAIAN1' => $this->PENCAPAIAN1,
            'PENCAPAIAN2' => $this->PENCAPAIAN2,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI]);

        return $dataProvider;
    }
}
