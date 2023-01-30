<?php

namespace backend\modules\makanan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\makanan\models\BarangSitaan;

/**
 * BarangSitaanSearch represents the model behind the search form of `backend\modules\makanan\models\BarangSitaan`.
 */
class BarangSitaanSearch extends BarangSitaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'HARGA'], 'number'],
            [['NOSIRI', 'JENISMAKANAN', 'PENGENALAN', 'KESALAHAN', 'NAMAPEMBUAT', 'ALAMATPEMBUAT', 'CATATAN'], 'safe'],
            [['KUANTITI', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        $query = BarangSitaan::find();

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
            'KUANTITI' => $this->KUANTITI,
            'HARGA' => $this->HARGA,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'JENISMAKANAN', $this->JENISMAKANAN])
            ->andFilterWhere(['like', 'PENGENALAN', $this->PENGENALAN])
            ->andFilterWhere(['like', 'KESALAHAN', $this->KESALAHAN])
            ->andFilterWhere(['like', 'NAMAPEMBUAT', $this->NAMAPEMBUAT])
            ->andFilterWhere(['like', 'ALAMATPEMBUAT', $this->ALAMATPEMBUAT])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        return $dataProvider;
    }
}
