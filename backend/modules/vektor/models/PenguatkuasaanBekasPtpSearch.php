<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\PenguatkuasaanBekasPtp;

/**
 * PenguatkuasaanBekasPtpSearch represents the model behind the search form of `backend\modules\vektor\models\PenguatkuasaanBekasPtp`.
 */
class PenguatkuasaanBekasPtpSearch extends PenguatkuasaanBekasPtp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'BILBEKAS', 'BILPOTENSI', 'BILPOSITIF', 'PURPA', 'KAWASAN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
            [['NOSIRI', 'NOSAMPEL', 'JENISBEKAS', 'KEPUTUSAN'], 'safe'],
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
    public function search($params, $nosiri = null, $nosampel = null)
    {
        $query = PenguatkuasaanBekasPtp::find();

        // add conditions that should always apply here
        if ($nosiri && $nosampel) {
            $query->andWhere(['NOSIRI' => $nosiri])
            ->andWhere(['NOSAMPEL' => $nosampel]);
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
            'BILBEKAS' => $this->BILBEKAS,
            'BILPOTENSI' => $this->BILPOTENSI,
            'BILPOSITIF' => $this->BILPOSITIF,
            'PURPA' => $this->PURPA,
            'KAWASAN' => $this->KAWASAN,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'NOSAMPEL', $this->NOSAMPEL])
            ->andFilterWhere(['like', 'JENISBEKAS', $this->JENISBEKAS])
            ->andFilterWhere(['like', 'KEPUTUSAN', $this->KEPUTUSAN]);

        return $dataProvider;
    }
}
