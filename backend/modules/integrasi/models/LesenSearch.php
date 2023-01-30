<?php

namespace backend\modules\integrasi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use \common\models\Pengguna;
use backend\modules\integrasi\models\Penjaja;
use backend\modules\integrasi\models\LesenMaster;

/**
 * ZonAhliMajlisSearch represents the model behind the search form about `backend\modules\parameter\models\ZonAhliMajlis`.
 */
class LesenSearch extends LesenMaster 
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID_PERMOHONAN'], 'number'],
            [['JENIS_LESEN','AMAUN_LESEN','ID_KAWASAN'], 'safe'],
            [['NO_AKAUN'], 'safe'],
            [['LOKASI_MENJAJA'], 'safe'],
            [['JENIS_JUALAN'], 'safe'],
            [['KAWASAN'], 'safe'],
            [['JENIS_JAJAAN'], 'safe'],
            [['NO_KP_PEMOHON','NAMA_PEMOHON', 'NAMA_SYARIKAT' ], 'safe'],
            [['NO_DFT_SYKT','TARIKH_PERMOHONAN','JENIS_PREMIS','ALAMAT_PREMIS1', 'ALAMAT_PREMIS2', 'ALAMAT_PREMIS3', 'POSKOD'], 'safe'],
            [['STATUS_PERMOHONAN','TARIKH_BATAL_TANGGUH','KUMPULAN_LESEN','KETERANGAN_KUMPULAN', 'KATEGORI_LESEN','KETERANGAN_KATEGORI'], 'safe'],
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
    $query = LesenMaster::find()->joinWith('penjaja');
    // $query = LesenMaster::find();

        // var_dump($query);
        // exit();
        
        // $command = $query->createCommand();
        // $data = $command->queryAll();
	
        

        $sort = [
            'attributes' => [
                // '{{V_EJKP_MASTERLIST_LESEN}}.NO_AKAUN',
                // '{{V_EJKP_MASTERLIST_LESEN}}.ID_PERMOHONAN',
                'NO_AKAUN' => [
                    'asc' => ['{{V_EJKP_MASTERLIST_LESEN}}.NO_AKAUN' => SORT_ASC],
                    'desc' => ['{{V_EJKP_MASTERLIST_LESEN}}.NO_AKAUN' => SORT_DESC],
                ],
                'ID_PERMOHONAN' => [
                    'asc' => ['{{V_EJKP_MASTERLIST_LESEN}}.ID_PERMOHONAN' => SORT_ASC],
                    'desc' => ['{{V_EJKP_MASTERLIST_LESEN}}.ID_PERMOHONAN' => SORT_DESC],
                ],
                'NO_KP_PEMOHON',
                'NAMA_PEMOHON',
                'NAMA_SYARIKAT',
                'NO_DFT_SYKT',
                'TARIKH_PERMOHONAN',
                'ALAMAT_PREMIS1',
                'ALAMAT_PREMIS2',
                'ALAMAT_PREMIS3',
                'POSKOD',
                'STATUS_PERMOHONAN',
                'TARIKH_BATAL_TANGGUH',
                'JENIS_PREMIS',
                'KUMPULAN_LESEN',
                'KETERANGAN_KUMPULAN',
                'KATEGORI_LESEN',
                'KETERANGAN_KATEGORI',
                'JENIS_LESEN',
                'AMAUN_LESEN',
                'LOKASI_MENJAJA',
                'JENIS_JUALAN',
                'KAWASAN',
                'ID_KAWASAN',
                'JENIS_JAJAAN',
            ],
             //'defaultOrder' => ['{{V_EJKP_MASTERLIST_LESEN}}.NO_AKAUN' => SORT_ASC],
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

        $query->andFilterWhere(['like', '{{V_EJKP_MASTERLIST_LESEN}}.NO_AKAUN', $this->NO_AKAUN])
              ->andFilterWhere(['like', '{{V_EJKP_MASTERLIST_LESEN}}.ID_PERMOHONAN', $this->ID_PERMOHONAN])
              ->andFilterWhere(['like', 'NO_KP_PEMOHON', $this->NO_KP_PEMOHON])
              ->andFilterWhere(['like', 'NAMA_PEMOHON', $this->NAMA_PEMOHON])
              ->andFilterWhere(['like', 'NAMA_SYARIKAT', $this->NAMA_SYARIKAT])
              ->andFilterWhere(['like', 'NO_DFT_SYKT', $this->NO_DFT_SYKT])
              ->andFilterWhere(['like', 'TARIKH_PERMOHONAN', $this->TARIKH_PERMOHONAN])
              ->andFilterWhere(['like', 'ALAMAT_PREMIS1', $this->ALAMAT_PREMIS1])
              ->andFilterWhere(['like', 'ALAMAT_PREMIS2', $this->ALAMAT_PREMIS2])
              ->andFilterWhere(['like', 'ALAMAT_PREMIS3', $this->ALAMAT_PREMIS3])
              ->andFilterWhere(['like', 'POSKOD', $this->POSKOD])
              ->andFilterWhere(['like', 'STATUS_PERMOHONAN', $this->STATUS_PERMOHONAN])
              ->andFilterWhere(['like', 'TARIKH_BATAL_TANGGUH', $this->TARIKH_BATAL_TANGGUH])
              ->andFilterWhere(['like', 'KUMPULAN_LESEN', $this->KUMPULAN_LESEN])
              ->andFilterWhere(['like', 'TARIKH_PERMOHONAN', $this->TARIKH_PERMOHONAN])
              ->andFilterWhere(['like', 'JENIS_PREMIS', $this->JENIS_PREMIS])
              ->andFilterWhere(['like', 'KETERANGAN_KUMPULAN', $this->KETERANGAN_KUMPULAN])
              ->andFilterWhere(['like', 'KATEGORI_LESEN', $this->KATEGORI_LESEN])
              ->andFilterWhere(['like', 'KETERANGAN_KATEGORI', $this->KETERANGAN_KATEGORI])
              ->andFilterWhere(['like','{{V_EJKP_PENJAJA}}.JENIS_LESEN', $this->JENIS_LESEN])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.AMAUN_LESEN', $this->AMAUN_LESEN])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.LOKASI_MENJAJA', $this->LOKASI_MENJAJA])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.JENIS_JUALAN', $this->JENIS_JUALAN])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.KAWASAN', $this->KAWASAN])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.ID_KAWASAN', $this->ID_KAWASAN])
              ->andFilterWhere(['like', '{{V_EJKP_PENJAJA}}.JENIS_JAJAAN', $this->JENIS_JAJAAN]);
              

        return $dataProvider;
    }
}
