<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CountriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->NOSIRI;
$this->params['breadcrumbs'] = [
    ['label' => 'Pencegahan Vektor', 'url' => ['index']],
    'SRT',
    'Liputan Semburan',
    $this->title,
];
?>
<div class="liputantranssrt-index">

<!-- Render create form -->    
    <?= $this->render('_liputan-form', [
        'liputan' => $liputan,
    ]) ?>
    
    
<?php Pjax::begin(['id' => 'liputantranssrt']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end() ?>
</div>