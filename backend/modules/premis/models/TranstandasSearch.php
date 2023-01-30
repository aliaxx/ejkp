<?php

namespace backend\modules\premis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\premis\models\Transtandas;

/**
 * TranspremisSearch represents the model behind the search form of `backend\modules\peniaga\models\Transpremis`.
 */
class TranstandasSearch extends Transtandas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'KODPERKARA', 'KODKOMPONEN', 'KODPRGN', 'IDMODULE', 'KODKOMPONEN'], 'safe'],
            [['MARKAH','ML','MW','MO','MU', 'STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['CATATAN', 'DEMERIT'], 'safe'],
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
    
    public function search($params, $NOSIRI = null)
    {
        $query = Transtandas::find()->joinWith('updatedByUser');

        if ($NOSIRI) {
            $query->andWhere(['NOSIRI' => $NOSIRI]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        // $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
        //     ->andFilterWhere(['like', 'NOGERAI', $this->NOGERAI])
        //     ->andFilterWhere(['like', 'TRKHLAWATAN_GERAI', $this->TRKHLAWATAN_GERAI])
        //     ->andFilterWhere(['like', 'NORUJUKAN', $this->NORUJUKAN])
        //     ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        // var_dump($query);
        // exit();
        return $dataProvider;

    }

    public function searchkomponen($params, $NOSIRI = null)
    {
        $query = Transpremis::find()->joinWith('updatedByUser');

        
        if ($NOSIRI) {
            $query->andWhere(['NOSIRI' => $NOSIRI]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        // $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
        //     ->andFilterWhere(['like', 'NOGERAI', $this->NOGERAI])
        //     ->andFilterWhere(['like', 'TRKHLAWATAN_GERAI', $this->TRKHLAWATAN_GERAI])
        //     ->andFilterWhere(['like', 'NORUJUKAN', $this->NORUJUKAN])
        //     ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        return $dataProvider;
    }
}
