<?php

namespace backend\modules\peniaga\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\peniaga\models\Transgerai;

/**
 * TransgeraiSearch represents the model behind the search form of `backend\modules\peniaga\models\Transgerai`.
 */
class TransgeraiSearch extends Transgerai
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'NOGERAI', 'TRKHLAWATAN_GERAI', 'NORUJUKAN', 'CATATAN'], 'safe'],
            [['STATUSPEMANTAUAN', 'TINDAKANPENGUATKUASA', 'STATUSGERAI', 'PRGKP_GREASE', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        $query = Transgerai::find()->joinWith('updatedByUser');

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
            'STATUSPEMANTAUAN' => $this->STATUSPEMANTAUAN,
            'TINDAKANPENGUATKUASA' => $this->TINDAKANPENGUATKUASA,
            'STATUSGERAI' => $this->STATUSGERAI,
            'PRGKP_GREASE' => $this->PRGKP_GREASE,
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
