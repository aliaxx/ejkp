<?php

namespace backend\modules\premis\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\premis\models\Anugerah;

/**
 * AnugerahSearch represents the model behind the search form of `backend\modules\premis\models\Anugerah`.
 */
class AnugerahSearch extends Anugerah
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['IDMODULE', 'NOSIRI','NOLESEN' ,'NOSSM', 'TAHUN', 'GRED', 'CATATAN'], 'safe'],
            [['STATUS', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
    public function search($params)
    {
        // $query = Anugerah::find()->joinWith(['premis0','pemilik0']);
        $query = Anugerah::find();

        // add conditions that should always apply here

        // add conditions that should always apply here
        $sort = [
            'attributes' => [
                // 'TRKHMULA',
                // 'TRKHTAMAT', 
                // 'NOSIRI' => [
                //     'asc' => ['{{%LAWATAN_MAIN}}.NOSIRI' => SORT_ASC],
                //     'desc' => ['{{%LAWATAN_MAIN}}.NOSIRI' => SORT_DESC],
                //     ], 
                // 'JENISPREMIS'=> [//tarik jenispremis dari tblawatanmain
                //     'asc' => ['{{%LAWATAN_MAIN}}.JENISPREMIS' => SORT_ASC],
                //     'desc' => ['{{%LAWATAN_MAIN}}.JENISPREMIS' => SORT_DESC],
                //     ], 

            ],
        //    'defaultOrder' => ['TRKHAKHIR' => SORT_DESC],
        ];

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
            'NOLESEN' => $this->NOLESEN,
            'NOSSM' => $this->NOSSM,
            'STATUS' => $this->STATUS,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'IDMODULE', $this->IDMODULE])
            ->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'NOSSM', $this->NOSSM])
            ->andFilterWhere(['like', 'TAHUN', $this->TAHUN])
            ->andFilterWhere(['like', 'GRED', $this->GRED])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN]);
            

        return $dataProvider;
    }
}
