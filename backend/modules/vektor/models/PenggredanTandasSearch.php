<?php

namespace backend\modules\vektor\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\vektor\models\PenggredanTandas;

/**
 * PenggredanTandasSearch represents the model behind the search form of `backend\modules\vektor\models\PenggredanTandas`.
 */
class PenggredanTandasSearch extends PenggredanTandas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ID', 'UV_AMAUNRACUN', 'UV_AMAUNPELARUT', 'UV_AMAUNPETROL', 'PA_JUMRACUN1', 'PA_JUMRACUN2', 'PA_JUMRACUN3', 'JUMKOMPAUN', 'JUMNOTIS'], 'number'],
            [['NOSIRI', 'IDMODULE', 'PRGNLOKASI_AM', 'PRGNLOKASI', 'TRKHMULA', 'TRKHTAMAT', 'NOADUAN', 'LATITUD', 'LONGITUD', 'CATATAN', 'PK_NAMAPENYELIA', 
            'PK_NOKPPENYELIA', 'PK_JENISKOLAM', 'ST_NOWABAK', 'ST_NOAKTIVITI', 'ST_RUJUKANKES', 'UV_PA_NODAFTARKES', 'UV_PA_LOKALITI', 'UV_PA_KAWASAN', 
            'UV_TRKHONSET', 'UV_TRKHKEYIN', 'UV_TRKHNOTIFIKASI', 'UV_MASAMULAHUJAN', 'UV_MASATAMATHUJAN', 'UV_MASAMULAANGIN', 'UV_MASATAMATANGIN', 'PA_TRKHKEYINEDENGGI', 
            'PA_TRKHNOTIFIKASI', 'PA_NAMAKK', 'NAMAPENERIMA', 'NOKPPENERIMA'], 'safe'],
            [['JENISPREMIS', 'IDZON_AM', 'IDDUN', 'ID_TUJUAN', 'IDSUBUNIT', 'IDLOKASI', 'STATUS', 'STATUSREKOD', 'PGNDAFTAR', 'TRKHDAFTAR', 'PGNAKHIR', 'TRKHAKHIR', 
            'PP_PENGELUAR', 'PP_BILPENGENDALI', 'PP_SUNTIKAN_ANTITIFOID', 'PP_KURSUS_PENGENDALI', 'SM_ID_JENISSAMPEL', 'SI_ID_STOR', 'PK_JENISRAWATAN', 'PK_JUMPENGGUNA', 
            'ST_ID_SEMBURANSRT', 'ST_TLENGKAPDALAM', 'ST_TLENGKAPLUAR', 'ST_LENGKAP', 'ST_TPERIKSA', 'ST_BILPENDUDUK', 'ST_SB1', 'ST_SB2', 'ST_SB3', 'ST_SB4', 
            'UV_PA_JENISSEMBUR', 'UV_PA_KATLOKALITI', 'UV_PA_PUSINGAN', 'UV_PA_ID_SUREVEILAN', 'UV_HUJAN', 'UV_KEADAANHUJAN', 'UV_ANGIN', 'UV_KEADAANANGIN', 
            'UV_JENISMESIN', 'UV_BILMESIN', 'UV_ID_RACUN', 'UV_ID_PELARUT', 'PA_MINGGUEPID', 'PA_SASARANPREMIS1', 'PA_BILPREMIS1', 'PA_BILBEKAS1', 'PA_ID_JENISRACUN1', 
            'PA_SASARANPREMIS2', 'PA_BILPREMIS2', 'PA_BILBEKAS2', 'PA_ID_JENISRACUN2', 'PA_BILORANG', 'PA_BILPREMIS3', 'PA_ID_JENISRACUN3', 'PA_TEMPOH', 'PA_ID_ALASAN', 
            'PA_BILPENDUDUK', 'PA_BILBEKASMUSNAH', 'PA_TLENGKAPDALAM', 'PA_TLENGKAPLUAR', 'PA_LENGKAP', 'PA_TPERIKSA', 'PA_JUMSEBAB1', 'PA_JUMSEBAB2', 'PA_JUMSEBAB3', 
            'PA_JUMSEBAB4', 'PT_ID_JENISPREMISTANDAS'], 'integer'],
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
        $query = PenggredanTandas::find()->where(['IDMODULE' =>'PTS']);

        
        // add conditions that should always apply here

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
            'JENISPREMIS' => $this->JENISPREMIS,
            'IDZON_AM' => $this->IDZON_AM,
            'IDDUN' => $this->IDDUN,
            'ID_TUJUAN' => $this->ID_TUJUAN,
            'IDSUBUNIT' => $this->IDSUBUNIT,
            'IDLOKASI' => $this->IDLOKASI,
            'STATUS' => $this->STATUS,
            'STATUSREKOD' => $this->STATUSREKOD,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
            'PP_PENGELUAR' => $this->PP_PENGELUAR,
            'PP_BILPENGENDALI' => $this->PP_BILPENGENDALI,
            'PP_SUNTIKAN_ANTITIFOID' => $this->PP_SUNTIKAN_ANTITIFOID,
            'PP_KURSUS_PENGENDALI' => $this->PP_KURSUS_PENGENDALI,
            'SM_ID_JENISSAMPEL' => $this->SM_ID_JENISSAMPEL,
            'SI_ID_STOR' => $this->SI_ID_STOR,
            'PK_JENISRAWATAN' => $this->PK_JENISRAWATAN,
            'PK_JUMPENGGUNA' => $this->PK_JUMPENGGUNA,
            'ST_ID_SEMBURANSRT' => $this->ST_ID_SEMBURANSRT,
            'ST_TLENGKAPDALAM' => $this->ST_TLENGKAPDALAM,
            'ST_TLENGKAPLUAR' => $this->ST_TLENGKAPLUAR,
            'ST_LENGKAP' => $this->ST_LENGKAP,
            'ST_TPERIKSA' => $this->ST_TPERIKSA,
            'ST_BILPENDUDUK' => $this->ST_BILPENDUDUK,
            'ST_SB1' => $this->ST_SB1,
            'ST_SB2' => $this->ST_SB2,
            'ST_SB3' => $this->ST_SB3,
            'ST_SB4' => $this->ST_SB4,
            'UV_PA_JENISSEMBUR' => $this->UV_PA_JENISSEMBUR,
            'UV_PA_KATLOKALITI' => $this->UV_PA_KATLOKALITI,
            'UV_PA_PUSINGAN' => $this->UV_PA_PUSINGAN,
            'UV_PA_ID_SUREVEILAN' => $this->UV_PA_ID_SUREVEILAN,
            'UV_HUJAN' => $this->UV_HUJAN,
            'UV_KEADAANHUJAN' => $this->UV_KEADAANHUJAN,
            'UV_ANGIN' => $this->UV_ANGIN,
            'UV_KEADAANANGIN' => $this->UV_KEADAANANGIN,
            'UV_JENISMESIN' => $this->UV_JENISMESIN,
            'UV_BILMESIN' => $this->UV_BILMESIN,
            'UV_ID_RACUN' => $this->UV_ID_RACUN,
            'UV_AMAUNRACUN' => $this->UV_AMAUNRACUN,
            'UV_ID_PELARUT' => $this->UV_ID_PELARUT,
            'UV_AMAUNPELARUT' => $this->UV_AMAUNPELARUT,
            'UV_AMAUNPETROL' => $this->UV_AMAUNPETROL,
            'PA_MINGGUEPID' => $this->PA_MINGGUEPID,
            'PA_SASARANPREMIS1' => $this->PA_SASARANPREMIS1,
            'PA_BILPREMIS1' => $this->PA_BILPREMIS1,
            'PA_BILBEKAS1' => $this->PA_BILBEKAS1,
            'PA_ID_JENISRACUN1' => $this->PA_ID_JENISRACUN1,
            'PA_JUMRACUN1' => $this->PA_JUMRACUN1,
            'PA_SASARANPREMIS2' => $this->PA_SASARANPREMIS2,
            'PA_BILPREMIS2' => $this->PA_BILPREMIS2,
            'PA_BILBEKAS2' => $this->PA_BILBEKAS2,
            'PA_ID_JENISRACUN2' => $this->PA_ID_JENISRACUN2,
            'PA_JUMRACUN2' => $this->PA_JUMRACUN2,
            'PA_BILORANG' => $this->PA_BILORANG,
            'PA_BILPREMIS3' => $this->PA_BILPREMIS3,
            'PA_ID_JENISRACUN3' => $this->PA_ID_JENISRACUN3,
            'PA_JUMRACUN3' => $this->PA_JUMRACUN3,
            'PA_TEMPOH' => $this->PA_TEMPOH,
            'PA_ID_ALASAN' => $this->PA_ID_ALASAN,
            'PA_BILPENDUDUK' => $this->PA_BILPENDUDUK,
            'PA_BILBEKASMUSNAH' => $this->PA_BILBEKASMUSNAH,
            'PA_TLENGKAPDALAM' => $this->PA_TLENGKAPDALAM,
            'PA_TLENGKAPLUAR' => $this->PA_TLENGKAPLUAR,
            'PA_LENGKAP' => $this->PA_LENGKAP,
            'PA_TPERIKSA' => $this->PA_TPERIKSA,
            'PA_JUMSEBAB1' => $this->PA_JUMSEBAB1,
            'PA_JUMSEBAB2' => $this->PA_JUMSEBAB2,
            'PA_JUMSEBAB3' => $this->PA_JUMSEBAB3,
            'PA_JUMSEBAB4' => $this->PA_JUMSEBAB4,
            'PT_ID_JENISPREMISTANDAS' => $this->PT_ID_JENISPREMISTANDAS,
            'JUMKOMPAUN' => $this->JUMKOMPAUN,
            'JUMNOTIS' => $this->JUMNOTIS,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'IDMODULE', $this->IDMODULE])
            ->andFilterWhere(['like', 'PRGNLOKASI_AM', $this->PRGNLOKASI_AM])
            ->andFilterWhere(['like', 'PRGNLOKASI', $this->PRGNLOKASI])
            ->andFilterWhere(['like', 'TRKHMULA', $this->TRKHMULA])
            ->andFilterWhere(['like', 'TRKHTAMAT', $this->TRKHTAMAT])
            ->andFilterWhere(['like', 'NOADUAN', $this->NOADUAN])
            ->andFilterWhere(['like', 'LATITUD', $this->LATITUD])
            ->andFilterWhere(['like', 'LONGITUD', $this->LONGITUD])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN])
            ->andFilterWhere(['like', 'PK_NAMAPENYELIA', $this->PK_NAMAPENYELIA])
            ->andFilterWhere(['like', 'PK_NOKPPENYELIA', $this->PK_NOKPPENYELIA])
            ->andFilterWhere(['like', 'PK_JENISKOLAM', $this->PK_JENISKOLAM])
            ->andFilterWhere(['like', 'ST_NOWABAK', $this->ST_NOWABAK])
            ->andFilterWhere(['like', 'ST_NOAKTIVITI', $this->ST_NOAKTIVITI])
            ->andFilterWhere(['like', 'ST_RUJUKANKES', $this->ST_RUJUKANKES])
            ->andFilterWhere(['like', 'UV_PA_NODAFTARKES', $this->UV_PA_NODAFTARKES])
            ->andFilterWhere(['like', 'UV_PA_LOKALITI', $this->UV_PA_LOKALITI])
            ->andFilterWhere(['like', 'UV_PA_KAWASAN', $this->UV_PA_KAWASAN])
            ->andFilterWhere(['like', 'UV_TRKHONSET', $this->UV_TRKHONSET])
            ->andFilterWhere(['like', 'UV_TRKHKEYIN', $this->UV_TRKHKEYIN])
            ->andFilterWhere(['like', 'UV_TRKHNOTIFIKASI', $this->UV_TRKHNOTIFIKASI])
            ->andFilterWhere(['like', 'UV_MASAMULAHUJAN', $this->UV_MASAMULAHUJAN])
            ->andFilterWhere(['like', 'UV_MASATAMATHUJAN', $this->UV_MASATAMATHUJAN])
            ->andFilterWhere(['like', 'UV_MASAMULAANGIN', $this->UV_MASAMULAANGIN])
            ->andFilterWhere(['like', 'UV_MASATAMATANGIN', $this->UV_MASATAMATANGIN])
            ->andFilterWhere(['like', 'PA_TRKHKEYINEDENGGI', $this->PA_TRKHKEYINEDENGGI])
            ->andFilterWhere(['like', 'PA_TRKHNOTIFIKASI', $this->PA_TRKHNOTIFIKASI])
            ->andFilterWhere(['like', 'PA_NAMAKK', $this->PA_NAMAKK])
            ->andFilterWhere(['like', 'NAMAPENERIMA', $this->NAMAPENERIMA])
            ->andFilterWhere(['like', 'NOKPPENERIMA', $this->NOKPPENERIMA]);

        //     var_dump($query);
        // exit();
        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchkomponen($params)
    {
        $query = "SELECT * FROM TBPP_PERKARA_KOMPONEN_PRGN WHERE STATUS=1";
        // $query = PenggredanTandas::find()->where(['IDMODULE' =>'PPM']);

        // add conditions that should always apply here

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
            'JENISPREMIS' => $this->JENISPREMIS,
            'IDZON_AM' => $this->IDZON_AM,
            'IDDUN' => $this->IDDUN,
            'ID_TUJUAN' => $this->ID_TUJUAN,
            'IDSUBUNIT' => $this->IDSUBUNIT,
            'IDLOKASI' => $this->IDLOKASI,
            'STATUS' => $this->STATUS,
            'STATUSREKOD' => $this->STATUSREKOD,
            'PGNDAFTAR' => $this->PGNDAFTAR,
            'TRKHDAFTAR' => $this->TRKHDAFTAR,
            'PGNAKHIR' => $this->PGNAKHIR,
            'TRKHAKHIR' => $this->TRKHAKHIR,
            'PP_PENGELUAR' => $this->PP_PENGELUAR,
            'PP_BILPENGENDALI' => $this->PP_BILPENGENDALI,
            'PP_SUNTIKAN_ANTITIFOID' => $this->PP_SUNTIKAN_ANTITIFOID,
            'PP_KURSUS_PENGENDALI' => $this->PP_KURSUS_PENGENDALI,
            'SM_ID_JENISSAMPEL' => $this->SM_ID_JENISSAMPEL,
            'SI_ID_STOR' => $this->SI_ID_STOR,
            'PK_JENISRAWATAN' => $this->PK_JENISRAWATAN,
            'PK_JUMPENGGUNA' => $this->PK_JUMPENGGUNA,
            'ST_ID_SEMBURANSRT' => $this->ST_ID_SEMBURANSRT,
            'ST_TLENGKAPDALAM' => $this->ST_TLENGKAPDALAM,
            'ST_TLENGKAPLUAR' => $this->ST_TLENGKAPLUAR,
            'ST_LENGKAP' => $this->ST_LENGKAP,
            'ST_TPERIKSA' => $this->ST_TPERIKSA,
            'ST_BILPENDUDUK' => $this->ST_BILPENDUDUK,
            'ST_SB1' => $this->ST_SB1,
            'ST_SB2' => $this->ST_SB2,
            'ST_SB3' => $this->ST_SB3,
            'ST_SB4' => $this->ST_SB4,
            'UV_PA_JENISSEMBUR' => $this->UV_PA_JENISSEMBUR,
            'UV_PA_KATLOKALITI' => $this->UV_PA_KATLOKALITI,
            'UV_PA_PUSINGAN' => $this->UV_PA_PUSINGAN,
            'UV_PA_ID_SUREVEILAN' => $this->UV_PA_ID_SUREVEILAN,
            'UV_HUJAN' => $this->UV_HUJAN,
            'UV_KEADAANHUJAN' => $this->UV_KEADAANHUJAN,
            'UV_ANGIN' => $this->UV_ANGIN,
            'UV_KEADAANANGIN' => $this->UV_KEADAANANGIN,
            'UV_JENISMESIN' => $this->UV_JENISMESIN,
            'UV_BILMESIN' => $this->UV_BILMESIN,
            'UV_ID_RACUN' => $this->UV_ID_RACUN,
            'UV_AMAUNRACUN' => $this->UV_AMAUNRACUN,
            'UV_ID_PELARUT' => $this->UV_ID_PELARUT,
            'UV_AMAUNPELARUT' => $this->UV_AMAUNPELARUT,
            'UV_AMAUNPETROL' => $this->UV_AMAUNPETROL,
            'PA_MINGGUEPID' => $this->PA_MINGGUEPID,
            'PA_SASARANPREMIS1' => $this->PA_SASARANPREMIS1,
            'PA_BILPREMIS1' => $this->PA_BILPREMIS1,
            'PA_BILBEKAS1' => $this->PA_BILBEKAS1,
            'PA_ID_JENISRACUN1' => $this->PA_ID_JENISRACUN1,
            'PA_JUMRACUN1' => $this->PA_JUMRACUN1,
            'PA_SASARANPREMIS2' => $this->PA_SASARANPREMIS2,
            'PA_BILPREMIS2' => $this->PA_BILPREMIS2,
            'PA_BILBEKAS2' => $this->PA_BILBEKAS2,
            'PA_ID_JENISRACUN2' => $this->PA_ID_JENISRACUN2,
            'PA_JUMRACUN2' => $this->PA_JUMRACUN2,
            'PA_BILORANG' => $this->PA_BILORANG,
            'PA_BILPREMIS3' => $this->PA_BILPREMIS3,
            'PA_ID_JENISRACUN3' => $this->PA_ID_JENISRACUN3,
            'PA_JUMRACUN3' => $this->PA_JUMRACUN3,
            'PA_TEMPOH' => $this->PA_TEMPOH,
            'PA_ID_ALASAN' => $this->PA_ID_ALASAN,
            'PA_BILPENDUDUK' => $this->PA_BILPENDUDUK,
            'PA_BILBEKASMUSNAH' => $this->PA_BILBEKASMUSNAH,
            'PA_TLENGKAPDALAM' => $this->PA_TLENGKAPDALAM,
            'PA_TLENGKAPLUAR' => $this->PA_TLENGKAPLUAR,
            'PA_LENGKAP' => $this->PA_LENGKAP,
            'PA_TPERIKSA' => $this->PA_TPERIKSA,
            'PA_JUMSEBAB1' => $this->PA_JUMSEBAB1,
            'PA_JUMSEBAB2' => $this->PA_JUMSEBAB2,
            'PA_JUMSEBAB3' => $this->PA_JUMSEBAB3,
            'PA_JUMSEBAB4' => $this->PA_JUMSEBAB4,
            'PT_ID_JENISPREMISTANDAS' => $this->PT_ID_JENISPREMISTANDAS,
            'JUMKOMPAUN' => $this->JUMKOMPAUN,
            'JUMNOTIS' => $this->JUMNOTIS,
        ]);

        $query->andFilterWhere(['like', 'NOSIRI', $this->NOSIRI])
            ->andFilterWhere(['like', 'IDMODULE', $this->IDMODULE])
            ->andFilterWhere(['like', 'PRGNLOKASI_AM', $this->PRGNLOKASI_AM])
            ->andFilterWhere(['like', 'PRGNLOKASI', $this->PRGNLOKASI])
            ->andFilterWhere(['like', 'TRKHMULA', $this->TRKHMULA])
            ->andFilterWhere(['like', 'TRKHTAMAT', $this->TRKHTAMAT])
            ->andFilterWhere(['like', 'NOADUAN', $this->NOADUAN])
            ->andFilterWhere(['like', 'LATITUD', $this->LATITUD])
            ->andFilterWhere(['like', 'LONGITUD', $this->LONGITUD])
            ->andFilterWhere(['like', 'CATATAN', $this->CATATAN])
            ->andFilterWhere(['like', 'PK_NAMAPENYELIA', $this->PK_NAMAPENYELIA])
            ->andFilterWhere(['like', 'PK_NOKPPENYELIA', $this->PK_NOKPPENYELIA])
            ->andFilterWhere(['like', 'PK_JENISKOLAM', $this->PK_JENISKOLAM])
            ->andFilterWhere(['like', 'ST_NOWABAK', $this->ST_NOWABAK])
            ->andFilterWhere(['like', 'ST_NOAKTIVITI', $this->ST_NOAKTIVITI])
            ->andFilterWhere(['like', 'ST_RUJUKANKES', $this->ST_RUJUKANKES])
            ->andFilterWhere(['like', 'UV_PA_NODAFTARKES', $this->UV_PA_NODAFTARKES])
            ->andFilterWhere(['like', 'UV_PA_LOKALITI', $this->UV_PA_LOKALITI])
            ->andFilterWhere(['like', 'UV_PA_KAWASAN', $this->UV_PA_KAWASAN])
            ->andFilterWhere(['like', 'UV_TRKHONSET', $this->UV_TRKHONSET])
            ->andFilterWhere(['like', 'UV_TRKHKEYIN', $this->UV_TRKHKEYIN])
            ->andFilterWhere(['like', 'UV_TRKHNOTIFIKASI', $this->UV_TRKHNOTIFIKASI])
            ->andFilterWhere(['like', 'UV_MASAMULAHUJAN', $this->UV_MASAMULAHUJAN])
            ->andFilterWhere(['like', 'UV_MASATAMATHUJAN', $this->UV_MASATAMATHUJAN])
            ->andFilterWhere(['like', 'UV_MASAMULAANGIN', $this->UV_MASAMULAANGIN])
            ->andFilterWhere(['like', 'UV_MASATAMATANGIN', $this->UV_MASATAMATANGIN])
            ->andFilterWhere(['like', 'PA_TRKHKEYINEDENGGI', $this->PA_TRKHKEYINEDENGGI])
            ->andFilterWhere(['like', 'PA_TRKHNOTIFIKASI', $this->PA_TRKHNOTIFIKASI])
            ->andFilterWhere(['like', 'PA_NAMAKK', $this->PA_NAMAKK])
            ->andFilterWhere(['like', 'NAMAPENERIMA', $this->NAMAPENERIMA])
            ->andFilterWhere(['like', 'NOKPPENERIMA', $this->NOKPPENERIMA]);

        return $dataProvider;
    }
}
