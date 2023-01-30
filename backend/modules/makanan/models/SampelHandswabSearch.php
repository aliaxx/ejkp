<?php

namespace backend\modules\makanan\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\makanan\models\SampelHandswab;

/**
 * SampelHandswabSearch represents the model behind the search form of `backend\modules\makanan\models\SampelHandswab`.
 */
class SampelHandswabSearch extends SampelHandswab
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'IDSAMPEL', 'NAMAPEKERJA', 'JENIS', 'CATATAN', 'PERALATAN'], 'safe'],
            [['NOKP', 'TY2', 'FHC', 'KEPUTUSAN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
    public function search($params, $nosiri = null) //need to define $nosiri to carry nosiri for each record -NOR06092022
    {
        $query = SampelHandswab::find();

        // var_dump('<br>');
        // var_dump($query);
        // var_dump('<br>');
        // exit();

        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'pagination' => ['pageSizeLimit' => [1, 200]],
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
            'NOKP' => $this->NOKP,
            'TY2' => $this->TY2,
            'FHC' => $this->FHC,
            'KEPUTUSAN' => $this->KEPUTUSAN,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'IDSAMPEL', $this->IDSAMPEL])
            ->andFilterWhere(['like', 'NAMAPEKERJA', $this->NAMAPEKERJA])
            ->andFilterWhere(['like', 'JENIS', $this->JENIS])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        return $dataProvider;
    }
}
