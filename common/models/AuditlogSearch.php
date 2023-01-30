<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use common\models\AuditLog;
use common\utilities\DateTimeHelper;

/**
 * PerananSearch represents the model behind the search form about `common\models\Peranan`.
 */
class AuditLogSearch extends AuditLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TINDAKAN', ], 'integer'],
            [['TINDAKAN', 'NAMATABLE', 'URLMENU', 'TARIKHMASA', 'PENGGUNA'], 'safe'],
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
        $query = Auditlog::find()->joinWith('user');

        $sort = [
            'attributes' => [
                'TINDAKAN',
                'NAMATABLE',
                'URLMENU',
                'PENGGUNA' => [
                    'asc' => ['{{%PENGGUNA}}.nama' => SORT_ASC],
                    'desc' => ['{{%PENGGUNA}}.nama' => SORT_DESC],
                ],
                'TARIKHMASA',
            ],
            'defaultOrder' => [
                'TARIKHMASA' => SORT_DESC
            ],
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
            //'TINDAKAN' => $this->TINDAKAN,
        ])
        
        ->andFilterWhere(['like', 'TINDAKAN', $this->TINDAKAN])
        ->andFilterWhere(['like', 'NAMATABLE', $this->NAMATABLE])
        ->andFilterWhere(['like', 'URLMENU', $this->URLMENU])
        ->andFilterWhere(['like', '{{%PENGGUNA}}.NAMA', $this->PENGGUNA]);

        if (!empty($this->TARIKHMASA)) {
            $query->andFilterWhere(['>=', 'TARIKHMASA', strtotime(DateTimeHelper::convert($this->TARIKHMASA . ', 00:00'))]);
            $query->andFilterWhere(['<=', 'TARIKHMASA', strtotime(DateTimeHelper::convert($this->TARIKHMASA . ', 23:59'))]);
        } else {
            $query->andFilterWhere(['<=', 'TARIKHMASA', date('Y-m-d 23:59')]);
        }

        return $dataProvider;
    }
}
