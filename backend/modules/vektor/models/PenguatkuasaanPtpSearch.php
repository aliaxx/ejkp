<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\PenguatkuasaanPtp;

/**
 * PenguatkuasaanPtpSearch represents the model behind the search form of `backend\modules\vektor\models\PenguatkuasaanPtp`.
 */
class PenguatkuasaanPtpSearch extends PenguatkuasaanPtp
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['JENIS', 'ID_JENISPREMIS', 'LIPUTAN', 'ID_JENISPEMBIAKAN', 'ID_TINDAKAN', 'ID_SEBABNOTIS', 'BILBEKASMUSNAH', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'ID'], 'integer'],
            [['NOSIRI', 'NOSAMPEL', 'NOLOT', 'BANGUNAN', 'TAMAN', 'NAMAPESALAH', 'TRKHSALAH', 'LATITUDE', 'LONGITUDE'], 'safe'],
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
        $query = PenguatkuasaanPtp::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
                'ID',
                'JENIS',
                'ID_JENISPREMIS',
                'LIPUTAN',
                'ID_JENISPEMBIAKAN',
                'ID_TINDAKAN',
                'ID_SEBABNOTIS',
                'BILBEKASMUSNAH',
                'PGNAKHIR' => [
                'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'TRKHAKHIR',
                ],
            'defaultOrder' => ['ID' => SORT_ASC],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'JENIS' => $this->JENIS,
            'ID_JENISPREMIS' => $this->ID_JENISPREMIS,
            'LIPUTAN' => $this->LIPUTAN,
            'ID_JENISPEMBIAKAN' => $this->ID_JENISPEMBIAKAN,
            'ID_TINDAKAN' => $this->ID_TINDAKAN,
            'ID_SEBABNOTIS' => $this->ID_SEBABNOTIS,
            'BILBEKASMUSNAH' => $this->BILBEKASMUSNAH,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
            'ID' => $this->ID,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'NOSAMPEL', $this->NOSAMPEL])
            ->andFilterWhere(['like', 'NOLOT', $this->NOLOT])
            ->andFilterWhere(['like', 'BANGUNAN', $this->BANGUNAN])
            ->andFilterWhere(['like', 'TAMAN', $this->TAMAN])
            ->andFilterWhere(['like', 'NAMAPESALAH', $this->NAMAPESALAH])
            ->andFilterWhere(['like', 'TRKHSALAH', $this->TRKHSALAH]);

        return $dataProvider;
    }
}
