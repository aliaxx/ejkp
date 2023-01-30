<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use common\utilities\DateTimeHelper;

use common\models\Pengguna;
use backend\modules\lawatanmain\models\LawatanMain;

use backend\components\validator\LaporanTrkhMulaLaporan;
use backend\components\validator\LaporanTrkhTamatLaporan;


/**
 * LawatanMainSearch represents the model behind the search form of `backend\modules\lawatanmain\models\LawatanMain`.
 */
class LaporanSearch extends LawatanMain
{

    //$JENIS_PREMIS - lawatan pemilik, $JENISPREMIS - lawatanmain
    public $NAMAPEMOHON;
    public $JENIS_PREMIS;
    public $KETUAPASUKAN;
    public $jenislaporan, $_inputpgndaftar, $TRKHMULA, $TRKHTAMAT, $IDLOKASI, $NOPETAK; //for laporan

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['NOSIRI', 'JENISPREMIS', 'PRGNLOKASI','PRGNLOKASI_AM', 'IDDUN', 'NOADUAN'], 'safe'],
            [['PKK_NAMAPENYELIA', 'PKK_NOKPPENYELIA', 'PKK_JENISKOLAM', 'PKK_JENISRAWATAN'], 'safe'],
            [['NOLESEN', 'NOSSM', 'JENIS_PREMIS', 'NAMASYARIKAT', 'NAMAPREMIS', 'NAMAPEMOHON', 'NOKPPEMOHON'], 'safe'],
            [['KETUAPASUKAN'], 'safe'],
            [['NAMAPENERIMA', 'NOKPPENERIMA', 'STATUS', 'PGNDAFTAR','PGNAKHIR'], 'safe'],
            [['SDR_ID_STOR','SMM_ID_JENISSAMPEL','IDLOKASI', 'ID_TUJUAN' ], 'safe'],
            [['jenislaporan', 'NOPETAK','TRKHMULA','TRKHTAMAT'], 'safe'],

            [['TRKHMULA', 'TRKHTAMAT'], 'required'],

            // ['TRKHMULA', LaporanTrkhMulaLaporan::className()],
            // ['TRKHTAMAT', LaporanTrkhTamatLaporan::className()],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'TRKHMULA' => 'Tarikh Mula Lawatan',
            'TRKHTAMAT' => 'Tarikh Tamat Lawatan',
        ]);
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
        $query = LawatanMain::find();

            $sort = [
                'defaultOrder' => ['TRKHMULA' => SORT_DESC],
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
            $query->andFilterWhere(['>=', '{{%LAWATAN_MAIN}}.TRKHMULA', DateTimeHelper::convert($this->TRKHMULA . ', 12:00 PG')]);
            $query->andFilterWhere(['<=', '{{%LAWATAN_MAIN}}.TRKHTAMAT', DateTimeHelper::convert($this->TRKHTAMAT . ', 11:59 PTG')]);


        return $dataProvider;
    }
}
