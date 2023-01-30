<?php

namespace backend\modules\premis\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use common\utilities\DateTimeHelper;
use backend\modules\lawatanmain\models\LawatanMain;
use common\models\Pengguna;


/**
 * LawatanMainSearch represents the model behind the search form of `backend\modules\lawatanmain\models\LawatanMain`.
 */
class LawatanApbSearch extends LawatanMain
{

    //$JENIS_PREMIS - lawatan pemilik, $JENISPREMIS - lawatanmain
    public $NAMAPEMOHON;
    public $JENIS_PREMIS;
    public $KETUAPASUKAN;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['TRKHMULA','TRKHTAMAT',], 'required', 'on' => ['laporan']],
            [['TRKHMULA','TRKHTAMAT', 'NOSIRI', 'JENISPREMIS', 'PRGNLOKASI','PRGNLOKASI_AM', 'IDDUN', 'NOADUAN'], 'safe'],
            [['PKK_NAMAPENYELIA', 'PKK_NOKPPENYELIA', 'PKK_JENISKOLAM', 'PKK_JENISRAWATAN'], 'safe'],
            [['NOLESEN', 'NOSSM', 'JENIS_PREMIS', 'NAMASYARIKAT', 'NAMAPREMIS', 'NAMAPEMOHON', 'NOKPPEMOHON'], 'safe'],
            [['KETUAPASUKAN'], 'safe'],
            [['NAMAPENERIMA', 'NOKPPENERIMA', 'STATUS', 'PGNDAFTAR','PGNAKHIR'], 'safe'],
            [['SDR_ID_STOR','SMM_ID_JENISSAMPEL','IDLOKASI', 'ID_TUJUAN'], 'safe'],
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
    public function search($idmodule, $params)
    {
        // $query = LawatanMain::find()->joinWith(['pemilik0', 'dun0', 'jenis','stor','createdByUser','updatedByUser','ketuapasukan0.pengguna0 NAMAKETUAPASUKAN'])
        //         ->where(['{{%LAWATAN_MAIN}}.IDMODULE' => $idmodule]);   
                // ->andwhere(['{{%LAWATAN_MAIN}}.TRKHMULA' => $YEAR]);;


        $query = LawatanMain::find()->select(['{{%LAWATAN_MAIN}}.NOSIRI','{{%LAWATAN_PEMILIK}}.NAMASYARIKAT','{{%LAWATAN_PEMILIK}}.NOLESEN'])
        ->joinWith(['pemilik0','premis0' , 'createdByUser','updatedByUser'])
        ->where(['{{%LAWATAN_MAIN}}.IDMODULE' => $idmodule])
        ->groupBy(['{{%LAWATAN_MAIN}}.NOSIRI', '{{%LAWATAN_PEMILIK}}.NOSIRI', '{{%GRED_PREMIS}}.NOSIRI','{{%LAWATAN_PEMILIK}}.NAMASYARIKAT','{{%LAWATAN_PEMILIK}}.NOLESEN'])
        ->having(['>=','round((SUM("TBGRED_PREMIS".DEMERIT))/(SUM("TBGRED_PREMIS".MARKAH))*100)', 86]);
        // ->andFilterWhere(['>=', 'SUM({{%GRED_PREMIS}}.DEMERIT)) ',86]);
        // // ->andwhere(['{{%LAWATAN_MAIN}}.TRKHMULA' => $YEAR]);

        
        // var_dump($query);
        // exit;


        // add conditions that should always apply here
            $sort = [
                'attributes' => [
                    'TRKHMULA',
                    'TRKHTAMAT', 
                    'NOSIRI' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.NOSIRI' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.NOSIRI' => SORT_DESC],
                        ], 
                    'JENISPREMIS'=> [//tarik jenispremis dari tblawatanmain
                        'asc' => ['{{%LAWATAN_MAIN}}.JENISPREMIS' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.JENISPREMIS' => SORT_DESC],
                        ], 
                    // 'ID_TUJUAN' => [
                    //     'asc' => ['{{%PARAMETER_DETAIL}}.KODDETAIL' => SORT_ASC],
                    //     'desc' => ['{{%PARAMETER_DETAIL}}.KODDETAIL' => SORT_DESC],
                    //     ],
                    'PRGNLOKASI' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.PRGNLOKASI' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.PRGNLOKASI' => SORT_DESC],
                        ],
                    'PRGNLOKASI_AM' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.PRGNLOKASI_AM' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.PRGNLOKASI_AM' => SORT_DESC],
                        ],
                    'IDDUN'=> [
                        'asc' => ['{{%DUN}}.ID' => SORT_ASC],
                        'desc' => ['{{%DUN}}.ID' => SORT_DESC],
                        ],
                    'NOADUAN',
                    'SDR_ID_STOR',
                    'SMM_ID_JENISSAMPEL',
                    'IDLOKASI',
                    'ID_TUJUAN',
                    'PKK_NAMAPENYELIA', 
                    'PKK_NOKPPENYELIA', 
                    'PKK_JENISKOLAM', 
                    'PKK_JENISRAWATAN',
                    '{{%LAWATAN_PEMILIK}}.NOLESEN', 
                    'NOSSM', 
                    'JENIS_PREMIS'=> [//tarik jenis_premis dari tblawatanpemilik
                        'asc' => ['{{%LAWATAN_PEMILIK}}.JENISPREMIS' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_PEMILIK}}.JENISPREMIS' => SORT_DESC],
                        ], 
                    'NAMASYARIKAT', 
                    'NAMAPREMIS', 
                    'NAMAPEMOHON' => [
                        'asc' => ['{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_PEMILIK}}.NAMAPEMOHON' => SORT_DESC],
                        ], 
                    'NOKPPEMOHON',
                    'KETUAPASUKAN' => [
                        'asc' => ['{{NAMAKETUAPASUKAN}}.NAMA' => SORT_ASC],
                        'desc' => ['{{NAMAKETUAPASUKAN}}.NAMA' => SORT_DESC],
                    ],
                    'NAMAPENERIMA' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.NAMAPENERIMA' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.NAMAPENERIMA' => SORT_DESC],
                        ],
                    'NOKPPENERIMA' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.NOKPPENERIMA' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.NOKPPENERIMA' => SORT_DESC],
                        ],
                    'STATUS' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.STATUS' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.STATUS' => SORT_DESC],
                        ],
                    'PGNDAFTAR' => [
                        'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                        'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                        ], 
                    'PGNAKHIR' => [
                        'asc' => ['{{%PENGGUNA}}.NAMA' => SORT_ASC],
                        'desc' => ['{{%PENGGUNA}}.NAMA' => SORT_DESC],
                        ], 
                    'TRKHAKHIR' => [
                        'asc' => ['{{%LAWATAN_MAIN}}.TRKHAKHIR' => SORT_ASC],
                        'desc' => ['{{%LAWATAN_MAIN}}.TRKHAKHIR' => SORT_DESC],
                        ],

                ],
            //    'defaultOrder' => ['TRKHAKHIR' => SORT_DESC],
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

        if ($this->TRKHMULA && !is_null($this->TRKHMULA)) {
            if ($this->getScenario() == 'laporan' || strlen($this->TRKHMULA) == 10) {
                $query->andFilterWhere(['>=', '{{%LAWATAN_MAIN}}.TRKHMULA', DateTimeHelper::convert($this->TRKHMULA . ', 12:00 PG')]);
            } else {
                $query->andFilterWhere(['>=', '{{%LAWATAN_MAIN}}.TRKHMULA', DateTimeHelper::convert($this->TRKHMULA)]);
            }
        }
        
        if ($this->TRKHTAMAT && !is_null($this->TRKHTAMAT)) {
            if ($this->getScenario() == 'laporan' || strlen($this->TRKHTAMAT) == 10) {
                $query->andFilterWhere(['<=', '{{%LAWATAN_MAIN}}.TRKHTAMAT', DateTimeHelper::convert($this->TRKHTAMAT . ', 11:59 PTG')]);
            } else {
                $query->andFilterWhere(['<=', '{{%LAWATAN_MAIN}}.TRKHTAMAT', DateTimeHelper::convert($this->TRKHTAMAT)]);
            }
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ID' => $this->ID,
            '{{%LAWATAN_MAIN}}.JENISPREMIS' => $this->JENISPREMIS,
            '{{%LAWATAN_MAIN}}.STATUS' => $this->STATUS,
        ]);

      $query->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.NOSIRI)', strtolower($this->NOSIRI)],true)
            ->andFilterWhere(['like', 'LOWER({{%DUN}}.PRGNDUN)', strtolower($this->IDDUN)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.PRGNLOKASI)', strtolower($this->PRGNLOKASI)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.PRGNLOKASI_AM)', strtolower($this->PRGNLOKASI_AM)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.NOADUAN)', strtolower($this->NOADUAN)],true)
            ->andFilterWhere(['like', 'SDR_ID_STOR', $this->SDR_ID_STOR])
            ->andFilterWhere(['like', 'ID_TUJUAN', $this->ID_TUJUAN])
            ->andFilterWhere(['like', 'SMM_ID_JENISSAMPEL', $this->SMM_ID_JENISSAMPEL])
            ->andFilterWhere(['like', 'ID_TUJUAN', $this->ID_TUJUAN])
            ->andFilterWhere(['like', 'PKK_NAMAPENYELIA', $this->PKK_NAMAPENYELIA])
            ->andFilterWhere(['like', 'PKK_NOKPPENYELIA', $this->PKK_NOKPPENYELIA])
            ->andFilterWhere(['like', 'PKK_JENISKOLAM', $this->PKK_JENISKOLAM])
            ->andFilterWhere(['like', 'PKK_JENISRAWATAN', $this->PKK_JENISRAWATAN])
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NOLESEN)', strtolower($this->NOLESEN)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NOSSM)', strtolower($this->NOSSM)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.JENIS_PREMIS)', strtolower($this->JENIS_PREMIS)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NAMASYARIKAT)', strtolower($this->NAMASYARIKAT)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NAMAPREMIS)', strtolower($this->NAMAPREMIS)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NAMAPEMOHON)', strtolower($this->NAMAPEMOHON)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_PEMILIK}}.NOKPPEMOHON)', strtolower($this->NOKPPEMOHON)],true)
            ->andFilterWhere(['like', 'LOWER({{NAMAKETUAPASUKAN}}.NAMA)', strtolower($this->KETUAPASUKAN)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.NAMAPENERIMA)', strtolower($this->NAMAPENERIMA)],true)
            ->andFilterWhere(['like', 'LOWER({{%LAWATAN_MAIN}}.NOKPPENERIMA)', strtolower($this->NOKPPENERIMA)],true)
            ->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNDAFTAR)],true)
            ->andFilterWhere(['like', 'LOWER({{%PENGGUNA}}.NAMA)', strtolower($this->PGNAKHIR)],true);
        
        //$query1->andFilterWhere(['like', 'KETUAPASUKAN', $this->KETUAPASUKAN])   
       // $query1->andFilterWhere(['like', 'LOWER({{%LAWATAN_PASUKAN}}.{{%PENGGUNA}}.NAMA', strtolower($this->KETUAPASUKAN)],true);

        return $dataProvider;
    }
}
