<?php

namespace backend\modules\makanan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\makanan\models\SampelMakanan;

/**
 * SampelMakananSearch represents the model behind the search form of `backend\modules\makanan\models\SampelMakanan`.
 */
class SampelMakananSearch extends SampelMakanan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'HARGA'], 'number'],
            [['NOSIRI', 'NOSAMPEL', 'TRKHSAMPEL', 'JENIS_SAMPEL', 'JENAMA', 'PEMBEKAL', 'CATATAN'], 'safe'],
            [['ID_JENISANALISIS1', 'ID_JENISANALISIS2', 'ID_JENISANALISIS3', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        $query = SampelMakanan::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

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
            'ID_JENISANALISIS1' => $this->ID_JENISANALISIS1,
            'ID_JENISANALISIS2' => $this->ID_JENISANALISIS2,
            'ID_JENISANALISIS3' => $this->ID_JENISANALISIS3,
            'HARGA' => $this->HARGA,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'NOSAMPEL', $this->NOSAMPEL])
            ->andFilterWhere(['like', 'TRKHSAMPEL', $this->TRKHSAMPEL])
            ->andFilterWhere(['like', 'JENIS_SAMPEL', $this->JENIS_SAMPEL])
            ->andFilterWhere(['like', 'JENAMA', $this->JENAMA])
            ->andFilterWhere(['like', 'PEMBEKAL', $this->PEMBEKAL])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        return $dataProvider;
    }
}
