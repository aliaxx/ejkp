<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form.
 */
class UserSearch extends User
{
   //public $kodnegeri;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'DATA_FILTER', 'PERANAN', 'STATUS'], 'integer'],
            [['AUTH_KEY', 'NOKP', 'KATA_LALUAN', 'SUBUNIT', 'NAMA'], 'safe'],
            [['PGNAKHIR', 'PGNDAFTAR'], 'safe'],
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
  //  public function search($params,$kodnegeri = null,$kodcawangan = null)
    public function search($params)
    {
       // $query = User::find()->joinWith(['updatedByUser']);
       $query = User::find();

        $enum = \common\utilities\OptionHandler::render('PERANAN');
        asort($enum);
        $fields['PERANAN'] = implode(',', array_keys($enum));

        $enum = \common\utilities\OptionHandler::render('DATA_FILTER');
        asort($enum);
        $fields['DATA_FILTER'] = implode(',', array_keys($enum));

        // if ($kodnegeri) $query->andWhere(['{{%cawangan}}.kodnegeri' => $kodnegeri]);
        // if ($kodcawangan) $query->andWhere(['{{%pengguna}}.kodcawangan' => $kodcawangan]);

        $sort = [
            'attributes' => [
                'NOKP',
                'SUBUNIT',
                'NAMA',
                'STATUS',
                'PGNDAFTAR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'PGNAKHIR' => [
                    'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
                'PERANAN' => [
                    'asc' => ['FIELD({{%PENGGUNA}}.PERANAN, '.$fields['PERANAN'].')' => SORT_ASC],
                    'desc' => ['FIELD({{%PENGGUNA}}.PERANAN, '.$fields['PERANAN'].')' => SORT_DESC],
                ],
                'DATA_FILTER' => [
                    'asc' => ['FIELD({{%PENGGUNA}}.DATA_FILTER, '.$fields['DATA_FILTER'].')' => SORT_ASC],
                    'desc' => ['FIELD({{%PENGGUNA}}.DATA_FILTER, '.$fields['DATA_FILTER'].')' => SORT_DESC],
                ],
                'TRKHAKHIR',
            ],
        ];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => $sort,
            'pagination' => ['pageSizeLimit' => [1, 200]],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%PENGGUNA}}.DATA_FILTER' => $this->DATA_FILTER,
            '{{%PENGGUNA}}.PERANAN' => $this->PERANAN,
            '{{%PENGGUNA}}.STATUS' => $this->STATUS,
        ]);

        $query->andFilterWhere(['like', '{{%PENGGUNA}}.NOKP', $this->NOKP])
            ->andFilterWhere(['like', 'SUBUNIT', $this->SUBUNIT])
            ->andFilterWhere(['like', 'NAMA', $this->NAMA])
            ->andFilterWhere(['like', 'createdUser.NAMA', $this->PGNDAFTAR])
            ->andFilterWhere(['like', 'updatedUser.NAMA', $this->PGNAKHIR]);

        return $dataProvider;
    }
}
