<?php

namespace backend\modules\integrasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \common\models\Pengguna;
use backend\modules\integrasi\models\Sppj;

/**
 * ZonAhliMajlisSearch represents the model behind the search form about `backend\modules\parameter\models\ZonAhliMajlis`.
 */
class SppjSearch extends Sppj 
{
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['NOICMILIK','NODAFTAR'], 'safe'],
            [['NOKMP', 'KODAKTA','KODSALAH'], 'safe'],
            [['TRKHKMP','TRFKMP','TRKHBAYAR','KAUNTER'], 'safe'],
        ];
    }

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

        $query = Sppj::find();

      
        // $sort = [
        //     'attributes' => [
        //         'ACCOUNT_NUMBER' => [
        //             'asc' => ['ACCOUNT_NUMBER' => SORT_ASC],
        //             'desc' => ['ACCOUNT_NUMBER' => SORT_DESC],
        //         ],
        //         'LICENSE_NUMBER' => [
        //             'asc' => ['LICENSE_NUMBER' => SORT_ASC],
        //             'desc' => ['LICENSE_NUMBER' => SORT_DESC],
        //         ],
        //         'LOCATION_NAME' => [
        //             'asc' => ['LOCATION_NAME' => SORT_ASC],
        //             'desc' => ['LOCATION_NAME' => SORT_DESC],
        //         ],
                
        //     ],
        //      //'defaultOrder' => ['{{V_SEWA_EJKP}}.NO_AKAUN' => SORT_ASC],
        // ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort' => $sort,
            // 'pagination' => ['pageSizeLimit' => [1, 200]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'LOWER(NOICMILIK)', strtolower($this->NOICMILIK)],true)
              ->andFilterWhere(['like', 'LOWER(NODAFTAR)', strtolower($this->NODAFTAR)],true);

        return $dataProvider;
    }
}
