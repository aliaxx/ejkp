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
   public $kodnegeri;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'idgred', 'susunan', 'idbahagian', 'data_filter', 'peranan', 'status'], 'integer'],
            [['auth_key', 'nokp', 'kata_laluan', 'kodcawangan', 'nama', 'jantina', 'nama_bahagian', 'nama_gelaran', 'nama_gelaran_jawatan', 'nama_jawatan', 'gred', 'no_telefon', 'no_tel_bimbit', 'emel', 'gambar', 'aktif_staf', 'idgred_kumpulan', 'idskim', 'idtaraf_jawatan', 'idnegeri_bahagian', 'alamat', 'poskod', 'idnegeri', 'tarikh_lahir', 'bandar', 'seksyen', 'unit', 'singkatan_jawatan', 'pengguna_apps'], 'safe'],
            [['pgnakhir'], 'safe'],
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
    public function search($params,$kodnegeri = null,$kodcawangan = null)
    {
        $query = User::find()->joinWith(['updatedByUser as updatedUser','kodcawangan0', 'kodnegeri0']);

        $enum = \common\utilities\OptionHandler::render('peranan');
        asort($enum);
        $fields['peranan'] = implode(',', array_keys($enum));

        $enum = \common\utilities\OptionHandler::render('data_filter');
        asort($enum);
        $fields['data_filter'] = implode(',', array_keys($enum));

        if ($kodnegeri) $query->andWhere(['{{%cawangan}}.kodnegeri' => $kodnegeri]);
        if ($kodcawangan) $query->andWhere(['{{%pengguna}}.kodcawangan' => $kodcawangan]);

        $sort = [
            'attributes' => [
                'nokp',
                'kodcawangan',
                'nama',
                'jantina',
                'nama_bahagian',
                'nama_gelaran',
                'nama_gelaran_jawatan',
                'nama_jawatan',
                'gred',
                'idgred',
                'no_telefon',
                'no_tel_bimbit',
                'emel',
                'susunan',
                'gambar',
                'aktif_staf',
                'idbahagian',
                'idgred_kumpulan',
                'idskim',
                'idtaraf_jawatan',
                'idnegeri_bahagian',
                'alamat',
                'poskod',
                'idnegeri',
                'tarikh_lahir',
                'bandar',
                'seksyen',
                'unit',
                'singkatan_jawatan',
                'pengguna_apps',
                'status',
                'pgnakhir' => [
                    'asc' => ['{{%pengguna}}.nama' => SORT_ASC],
                    'desc' => ['{{%pengguna}}.nama' => SORT_DESC],
                ],
                'peranan' => [
                    'asc' => ['FIELD({{%pengguna}}.peranan, '.$fields['peranan'].')' => SORT_ASC],
                    'desc' => ['FIELD({{%pengguna}}.peranan, '.$fields['peranan'].')' => SORT_DESC],
                ],
                'data_filter' => [
                    'asc' => ['FIELD({{%pengguna}}.data_filter, '.$fields['data_filter'].')' => SORT_ASC],
                    'desc' => ['FIELD({{%pengguna}}.data_filter, '.$fields['data_filter'].')' => SORT_DESC],
                ],
                'trkhakhir',
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
            '{{%pengguna}}.idgred' => $this->idgred,
            '{{%pengguna}}.susunan' => $this->susunan,
            '{{%pengguna}}.idbahagian' => $this->idbahagian,
            '{{%pengguna}}.tarikh_lahir' => $this->tarikh_lahir,
            '{{%pengguna}}.data_filter' => $this->data_filter,
            '{{%pengguna}}.peranan' => $this->peranan,
            '{{%pengguna}}.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', '{{%pengguna}}.nokp', $this->nokp])
            ->andFilterWhere(['like', '{{%pengguna}}.kodcawangan', $this->kodcawangan])
            ->andFilterWhere(['like', '{{%pengguna}}.nama', $this->nama])
            ->andFilterWhere(['like', '{{%pengguna}}.jantina', $this->jantina])
            ->andFilterWhere(['like', '{{%pengguna}}.nama_bahagian', $this->nama_bahagian])
            ->andFilterWhere(['like', '{{%pengguna}}.nama_gelaran', $this->nama_gelaran])
            ->andFilterWhere(['like', '{{%pengguna}}.nama_gelaran_jawatan', $this->nama_gelaran_jawatan])
            ->andFilterWhere(['like', '{{%pengguna}}.nama_jawatan', $this->nama_jawatan])
            ->andFilterWhere(['like', '{{%pengguna}}.gred', $this->gred])
            ->andFilterWhere(['like', '{{%pengguna}}.no_telefon', $this->no_telefon])
            ->andFilterWhere(['like', '{{%pengguna}}.no_tel_bimbit', $this->no_tel_bimbit])
            ->andFilterWhere(['like', '{{%pengguna}}.emel', $this->emel])
            ->andFilterWhere(['like', '{{%pengguna}}.gambar', $this->gambar])
            ->andFilterWhere(['like', '{{%pengguna}}.aktif_staf', $this->aktif_staf])
            ->andFilterWhere(['like', '{{%pengguna}}.idgred_kumpulan', $this->idgred_kumpulan])
            ->andFilterWhere(['like', '{{%pengguna}}.idskim', $this->idskim])
            ->andFilterWhere(['like', '{{%pengguna}}.idtaraf_jawatan', $this->idtaraf_jawatan])
            ->andFilterWhere(['like', '{{%pengguna}}.idnegeri_bahagian', $this->idnegeri_bahagian])
            ->andFilterWhere(['like', '{{%pengguna}}.alamat', $this->alamat])
            ->andFilterWhere(['like', '{{%pengguna}}.poskod', $this->poskod])
            ->andFilterWhere(['like', '{{%pengguna}}.idnegeri', $this->idnegeri])
            ->andFilterWhere(['like', '{{%pengguna}}.bandar', $this->bandar])
            ->andFilterWhere(['like', '{{%pengguna}}.seksyen', $this->seksyen])
            ->andFilterWhere(['like', '{{%pengguna}}.unit', $this->unit])
            ->andFilterWhere(['like', '{{%pengguna}}.singkatan_jawatan', $this->singkatan_jawatan])
            ->andFilterWhere(['like', '{{%pengguna}}.pengguna_apps', $this->pengguna_apps])
            ->andFilterWhere(['like', 'updatedUser.nama', $this->pgnakhir]);

        return $dataProvider;
    }
}
