<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\Lvcaktiviti;

/**
 * LvcaktivitiSeacrh represents the model behind the search form of `backend\modules\vektor\models\Lvcaktiviti`.
 */
class LvcaktivitiSeacrh extends Lvcaktiviti
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'V_SASARANPREMIS', 'V_BILPREMIS', 'V_BILBEKAS', 'V_ID_JENISRACUN', 'V_JUMRACUN', 'V_BILMESIN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 'AKTIVITI'], 'safe'],
            [['NOSIRI'], 'safe'],
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
        $query = Lvcaktiviti::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
                'ID',
                'V_SASARANPREMIS',
                'V_BILPREMIS',
                'V_BILBEKAS',
                'V_ID_JENISRACUN',
                'V_JUMRACUN',
                'V_BILMESIN',
                // 'PGNDAFTAR',
                // 'TRKHDAFTAR',
                'AKTIVITI',               
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
            'ID' => $this->ID,
            'V_SASARANPREMIS' => $this->V_SASARANPREMIS,
            'V_BILPREMIS' => $this->V_BILPREMIS,
            'V_BILBEKAS' => $this->V_BILBEKAS,
            'V_ID_JENISRACUN' => $this->V_ID_JENISRACUN,
            'V_JUMRACUN' => $this->V_JUMRACUN,
            'V_BILMESIN' => $this->V_BILMESIN,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
            'AKTIVITI' => $this->AKTIVITI,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI]);

        return $dataProvider;
    }
}
