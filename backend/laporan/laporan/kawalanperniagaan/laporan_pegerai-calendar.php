<?php

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use marekpetras\calendarview\CalendarView;

// $this->registerCssFile(Yii::getAlias('@vendor').'\marekpetras\yii2-calendarview-widget\assets\marekpetras.calendar.js');
// $this->registerCssFile(Yii::getAlias('@vendor').'\marekpetras\yii2-calendarview-widget\assets\marekpetras.calendar.css');
?>
<!-- nak ada title, boleh buka comment $title dekat marekpetras/yii2-calendarview-widget/views/calendar.php  -->
<?= CalendarView::widget(
    [
        // mandatory
        'dataProvider'  => $dataProvider,
        'dateField'     => 'TRKHLAWATAN_GERAI',
        'valueField'    => 'STATUSPEMANTAUAN_PRGN',


        // optional params with their defaults
        'unixTimestamp' => false, // indicate whether you use unix timestamp instead of a date/datetime format in the data provider
        'weekStart' => 1, // date('w') // which day to display first in the calendar
        // 'title'     => 'Calendar',

        'views'     => [
            'calendar' => '@vendor/marekpetras/yii2-calendarview-widget/views/calendar',
            'month' => '@vendor/marekpetras/yii2-calendarview-widget/views/month',
            'day' => '@vendor/marekpetras/yii2-calendarview-widget/views/day',
        ],

        'startYear' => date('Y') - 1,
        'endYear' => date('Y') + 1,

        'link' => false,
        /* alternates to link , is called on every models valueField, used in Html::a( valueField , link )
        'link' => 'site/view',
        'link' => function($model,$calendar){
            return ['calendar/view','id'=>$model->id];
        },
        */

        'dayRender' => false,
        /* alternate to dayRender
        'dayRender' => function($model,$calendar) {
            return '<p>'.$model->id.'</p>';
        },
        */

    ]
);
?>

