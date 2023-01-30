<?php

namespace backend\modules\integrasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \common\models\Pengguna;
use backend\modules\integrasi\models\Sewa;

/**
 * ZonAhliMajlisSearch represents the model behind the search form about `backend\modules\parameter\models\ZonAhliMajlis`.
 */
class SewaSearch extends Sewa 
{
    /**
     * @inheritdoc
     */
    /**
     * @inheritdoc
     */

    public function rules()
    {
        return [
            [['ACCOUNT_NUMBER', 'LICENSE_NUMBER'], 'safe'],
            [['NAME'],'safe'],
            [['ADDRESS_1','ADDRESS_2','ADDRESS_3','LOCATION_NAME'], 'safe'],
            [['ADDRESS_POSTCODE','LOT_NO','ASSET_ADDRESS_POSTCODE'], 'safe'],
            [['LOCATION_ID'], 'number'],
            [['RENT_CATEGORY','SALES_TYPE'], 'safe'],
            [['ASSET_ADDRESS_1','ASSET_ADDRESS_2','ASSET_ADDRESS_3'], 'safe'],
            [['ASSET_ADDRESS_LAT','ASSET_ADDRESS_LONG'], 'safe'],
            [['RENT_AMOUNT','OUTSTANDING_RENT_AMOUNT'], 'number'],
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

        $query = Sewa::find();

      
        $sort = [
            'attributes' => [
                'ACCOUNT_NUMBER' => [
                    'asc' => ['ACCOUNT_NUMBER' => SORT_ASC],
                    'desc' => ['ACCOUNT_NUMBER' => SORT_DESC],
                ],
                'LICENSE_NUMBER' => [
                    'asc' => ['LICENSE_NUMBER' => SORT_ASC],
                    'desc' => ['LICENSE_NUMBER' => SORT_DESC],
                ],
                'LOCATION_NAME' => [
                    'asc' => ['LOCATION_NAME' => SORT_ASC],
                    'desc' => ['LOCATION_NAME' => SORT_DESC],
                ],
                
            ],
             //'defaultOrder' => ['{{V_SEWA_EJKP}}.NO_AKAUN' => SORT_ASC],
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

        $query->andFilterWhere(['like', 'LOWER(ACCOUNT_NUMBER)', strtolower($this->ACCOUNT_NUMBER)],true)
              ->andFilterWhere(['like', 'LOWER(LICENSE_NUMBER)', strtolower($this->LICENSE_NUMBER)],true)
              ->andFilterWhere(['like', 'LOWER(NAME)', strtolower($this->NAME)],true)
              ->andFilterWhere(['like', 'LOWER(LOT_NO)', strtolower($this->LOT_NO)],true)
              ->andFilterWhere(['like', 'LOWER(ADDRESS_1)', strtolower($this->ADDRESS_1)],true)
              ->andFilterWhere(['like', 'LOWER(ADDRESS_2)', strtolower($this->ADDRESS_2)],true)
              ->andFilterWhere(['like', 'LOWER(ADDRESS_3)', strtolower($this->ADDRESS_3)],true)
              ->andFilterWhere(['like', 'LOWER(SALES_TYPE)', strtolower($this->SALES_TYPE)],true)
              ->andFilterWhere(['like', 'ADDRESS_POSTCODE', $this->ADDRESS_POSTCODE])
              ->andFilterWhere(['like', 'LOWER(LOCATION_NAME)', strtolower($this->LOCATION_NAME)],true)
              ->andFilterWhere(['like', 'LOWER(RENT_CATEGORY)', strtolower($this->RENT_CATEGORY)],true)
              ->andFilterWhere(['like', 'LOWER(ASSET_ADDRESS_1)', strtolower($this->ASSET_ADDRESS_1)],true)
              ->andFilterWhere(['like', 'LOWER(ASSET_ADDRESS_2)', strtolower($this->ASSET_ADDRESS_2)],true)
              ->andFilterWhere(['like', 'LOWER(ASSET_ADDRESS_3)', strtolower($this->ASSET_ADDRESS_3)],true)
              ->andFilterWhere(['like', 'LOWER(ASSET_ADDRESS_POSTCODE)', strtolower($this->ASSET_ADDRESS_POSTCODE)],true)
              ->andFilterWhere(['like', 'ASSET_ADDRESS_LAT', $this->ASSET_ADDRESS_LAT])
              ->andFilterWhere(['like', 'ASSET_ADDRESS_LONG', $this->ASSET_ADDRESS_LONG])
              ->andFilterWhere(['like', 'RENT_AMOUNT', $this->RENT_AMOUNT])
              ->andFilterWhere(['like', 'OUTSTANDING_RENT_AMOUNT', $this->OUTSTANDING_RENT_AMOUNT]);

        return $dataProvider;
    }
}
