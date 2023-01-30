<?php

namespace backend\modules\vektor\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\utilities\DateTimeHelper;
use backend\modules\vektor\models\PemeriksaanTandas;
use backend\modules\penyelenggaraan\models\ParamDetail;
use common\models\Pengguna;

/**
 * PemeriksaanTandasSearch represents the model behind the search form about `backend\modules\PemeriksaanTandas\models\PemeriksaanTandas`.
 */
class PemeriksaanTandasSearch extends PemeriksaanTandas
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //put the attributes that gonna be filtering at grid table index page.
        return [
            [['NOSIRI', 'NOTEL','KODDETAIL_KATTANDAS', 'IDZONAM', 'LONGITUD', 'LATITUD', 'PEMERIKSA','NOSIRI_PP'], 'safe'],
            [['STATUS'], 'integer'],
            [['KODJENIS_KATTANDAS','KODDETAIL_KATTANDAS', 'IDZONAM', 'LONGITUD', 'LATITUD', 'PEMERIKSA'], 'safe'],
            [['PGNDAFTAR', 'PGNAKHIR'], 'safe'],
            [['NOSSM', 'NAMAPEMOHON', 'NOKPPEMOHON', 'NOKPPENERIMA', 'NAMASYARIKAT', 'NOLESEN', 'NAMAPENERIMA', 'NAMAPREMIS'], 'safe'],
            [['ALAMAT1', 'ALAMAT2', 'ALAMAT3', 'POSKOD'], 'safe'],
            [['TARIKHLAWATANMULA', 'TARIKHLAWATANTAMAT'], 'safe'],
        ];
    }

   /**
     * @inheritdoc
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
       // $query = PemeriksaanTandas::find()->joinWith(['kategoriPremis', 'ahliMajlis', 'pegawaiPemeriksa', 'updatedByUser']);
       $query = PemeriksaanTandas::find()->joinWith(['ahliMajlis', 'pegawaiPemeriksa', 'createdByUser', 'updatedByUser']); 
       //$query = PemeriksaanTandas::find();

        $sort = [
            'attributes' => [
            'NOSIRI',
            'NOSIRI_PP',
            'NOLESEN',
            'NOKPPEMOHON',
            'NAMAPEMOHON',
            'NOTEL',
            'NOSSM',
            'NAMASYARIKAT',
            'ALAMAT1',
            'ALAMAT2',
            'ALAMAT3',
            'POSKOD',
            'TARIKHLAWATANMULA',
            'TARIKHLAWATANTAMAT',
            'KODDETAIL_KATTANDAS' ,
            'IDZONAM' => [
                'asc' => ['{{TBZON_AHLIMAJLIS}}.ID' => SORT_ASC],
                'desc' => ['{{TBZON_AHLIMAJLIS}}.ID' => SORT_DESC],
                ],
            'LONGITUD',
            'LATITUD',
            'PEMERIKSA' => [
                'asc' => ['{{%PENGGUNA}}.ID' => SORT_ASC],
                'desc' => ['{{%PENGGUNA}}.ID' => SORT_DESC],
                ],
            'NAMAPENERIMA',
            'NAMAPREMIS',
            'NOKPPENERIMA',
            'PGNDAFTAR' => [
                'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
            'PGNAKHIR' => [
                'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                ],
            'TRKHAKHIR',
            'STATUS',
            ],
           // 'defaultOrder' => ['NOSIRI' => SORT_ASC],
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

        $query->andFilterWhere(['{{TBPV_PEMERIKSAANTANDAS}}.STATUS' => $this->STATUS]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
        ->andFilterWhere(['like', 'NOSIRI_PP', $this->NOSIRI_PP])
        ->andFilterWhere(['like', 'NOLESEN', $this->NOLESEN])
        ->andFilterWhere(['like', 'NOKPPEMOHON', $this->NOKPPEMOHON])
        ->andFilterWhere(['like', 'NAMAPEMOHON', $this->NAMAPEMOHON])
        ->andFilterWhere(['like', 'NOTEL', $this->NOTEL])
        ->andFilterWhere(['like', 'NOSSM', $this->NOSSM])
        ->andFilterWhere(['like', 'NAMASYARIKAT', $this->NAMASYARIKAT])
        ->andFilterWhere(['like', 'ALAMAT1', $this->ALAMAT1])
        ->andFilterWhere(['like', 'ALAMAT2', $this->ALAMAT2])
        ->andFilterWhere(['like', 'ALAMAT3', $this->ALAMAT3])
        ->andFilterWhere(['like', 'POSKOD', $this->POSKOD])
        ->andFilterWhere(['like', 'TARIKHLAWATANMULA', $this->TARIKHLAWATANMULA])
        ->andFilterWhere(['like', 'TARIKHLAWATANTAMAT', $this->TARIKHLAWATANTAMAT])
        ->andFilterWhere(['like', 'KODDETAIL_KATTANDAS', $this->KODDETAIL_KATTANDAS])
        ->andFilterWhere(['like', '{{%ZON_AHLIMAJLIS}}.NAMAAHLIMAJLIS', $this->IDZONAM])
        ->andFilterWhere(['like', 'LONGITUD', $this->LONGITUD])
        ->andFilterWhere(['like', 'LATITUD', $this->LATITUD])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PEMERIKSA])
        ->andFilterWhere(['like', 'NAMAPENERIMA', $this->NAMAPENERIMA])
        ->andFilterWhere(['like', 'NOKPPENERIMA', $this->NOKPPENERIMA])
        ->andFilterWhere(['like', 'NAMAPREMIS', $this->NAMAPREMIS])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNDAFTAR])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PGNAKHIR]);
        return $dataProvider;
    }
}
