<?php
namespace backend\modules\makanan\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use backend\modules\makanan\models\Transkolam;

class TranskolamSearch extends Transkolam
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID'], 'number'],
            [['NOSIRI', 'IDPARAM'], 'safe'],
            [['NILAI1', 'NILAI2', 'NILAI3', 'NILAI4', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR'], 'integer'],
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
        //$query = Transkolam::find()->joinWith(['updatedByUser','createdByUser','dataParam']);
        $query = Transkolam::find();

        if ($nosiri) {
            $query->andWhere(['NOSIRI' => $nosiri]);
        }

        $sort = [
            'attributes' => [
                'ID',
                'NOSIRI',
                'IDPARAM',
                'NILAI1',
                'NILAI2',
                'NILAI3',
                'NILAI4',
                'PGNDAFTAR',
                'TRKHDAFTAR',
                'PGNAKHIR',
                'TRKHAKHIR',
            ],
            'defaultOrder' => ['NOSIRI' => SORT_DESC],
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
            'NOSIRI' => $this->NOSIRI,
            'IDPARAM'=> $this->IDPARAM,
            'NILAI1'=> $this->NILAI1,
            'NILAI2'=> $this->NILAI2,
            'NILAI3'=> $this->NILAI3,
            'NILAI4'=> $this->NILAI4,
        ]);
        //query ini hanya yang display di halaman Perkara
        $query->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNDAFTAR)],true)
              ->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNAKHIR)],true);

        return $dataProvider;
    }
}
