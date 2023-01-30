<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\RacunSrt;

/**
 * RacunSrtSearch represents the model behind the search form of `backend\modules\vektor\models\RacunSrt`.
 */
class RacunSrtSearch extends RacunSrt
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'AMAUNRACUN', 'AMAUNPELARUT', 'AMAUNPETROL'], 'number'],
            [['NOSIRI', 'ID_JENISPELARUT'], 'safe'],
            [['ID_PENGGUNAANRACUN', 'ID_JENISRACUNSRTULV', 'BILCAJ', 'BILMESIN', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        $query = RacunSrt::find();

        // add conditions that should always apply here
        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
                'ID',
                'ID_PENGGUNAANRACUN',
                'ID_JENISRACUNSRTULV',
                // 'kodtujuan' => [
                //     'asc' => ['{{%tujuanlawatan}}.prgn' => SORT_ASC],
                //     'desc' => ['{{%tujuanlawatan}}.prgn' => SORT_DESC],
                // ],
                // 'pgnakhir' => [
                //     'asc' => ['{{%pengguna}}.nama' => SORT_ASC],
                //     'desc' => ['{{%pengguna}}.nama' => SORT_DESC],
                // ],
                // 'trkhakhir',
            ],
            'defaultOrder' => ['ID' => SORT_ASC],
        ];


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => ['pageSizeLimit' => [1, 200]],
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
            'ID_PENGGUNAANRACUN' => $this->ID_PENGGUNAANRACUN,
            'ID_JENISRACUNSRTULV' => $this->ID_JENISRACUNSRTULV,
            'BILCAJ' => $this->BILCAJ,
            'BILMESIN' => $this->BILMESIN,
            'AMAUNRACUN' => $this->AMAUNRACUN,
            'AMAUNPELARUT' => $this->AMAUNPELARUT,
            'AMAUNPETROL' => $this->AMAUNPETROL,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'ID_JENISPELARUT', $this->ID_JENISPELARUT]);

        return $dataProvider;
    }
}
