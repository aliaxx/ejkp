<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

use backend\models\Calendar;

class CalendarSearch extends Calendar
{
    public function search($params)
    {
        
        $query = Calendar::find();

        $dataProvider = new ActiveDataProvider([
             'query' => $query,
             'pagination' => ['pageSize' => 30],
             'sort'=> ['defaultOrder' => ['start'=>SORT_DESC]]
         ]);

        //  var_dump($dataProvider);
        // exit;

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'DATE' => $this->TARIKH,
            'VALUE' => $this->VALUE,
        ]);

        return $dataProvider;
    }
}

