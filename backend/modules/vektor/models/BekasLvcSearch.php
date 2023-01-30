<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\BekasLvc;

/**
 * BekasPtpSearch represents the model behind the search form of `backend\modules\vektor\models\BekasPtp`.
 */
class BekasLvcSearch extends BekasLvc
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'JENISBEKAS', 'KEPUTUSAN', 'PURPA', 'CATATAN'], 'safe'],
            [['KAWASAN', 'BILBEKAS', 'BILPOTENSI', 'BILPOSITIF', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        $query = BekasLvc::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
            'ID',
            'JENISBEKAS', 
            'KEPUTUSAN', 
            'PURPA', 
            'KAWASAN', 
            'BILBEKAS', 
            'BILPOTENSI', 
            'BILPOSITIF', 
            'CATATAN',            
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
            'KAWASAN' => $this->KAWASAN,
            'BILBEKAS' => $this->BILBEKAS,
            'BILPOTENSI' => $this->BILPOTENSI,
            'BILPOSITIF' => $this->BILPOSITIF,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'JENISBEKAS', $this->JENISBEKAS])
            ->andFilterWhere(['like', 'KEPUTUSAN', $this->KEPUTUSAN])
            ->andFilterWhere(['like', 'PURPA', $this->PURPA])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);

        return $dataProvider;
    }
}
