<?php

// use common\models\LawatanMain;
use common\utilities\DateTimeHelper;
use common\models\Pengguna;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use common\utilities\OptionHandler;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\kpp\models\KppSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//dropdown search dekat grid table untuk kategori premis. 
// $source = \backend\modules\penyelenggaraan\models\ParamDetail::find()->where(['KODJENIS' => 1])->all();
// $option['kategori'] = \yii\helpers\ArrayHelper::map($source, 'KODDETAIL', 'PRGN');

$this->title = 'Carian Premis';
$this->params['breadcrumbs'] =['Penggredan Premis Makanan','Anugerah Premis Bersih'];

// var_dump($searchModel);
// exit;

?>

<div>
    <?= $this->render('@backend/modules/lawatanmain/views/_carianlawatan', [
        'model' => $searchModel,
    ]) ?>
    <div class="horizontal-divider"></div>

    <!-- <div class="row">
        <div class="col-md-12">
            <span class="pull-right">
            <?= Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Daftar', ['/premis/anugerah/create'],
            ['class' => 'btn btn-success']) ?>
            <p>
            </span>
        </div>
    </div> -->

    <?php $form = ActiveForm::begin(); ?>
    <?= \codetitan\widgets\ActionBar::widget([
        'target' => 'primary-grid',
        'permissions' => ['new' => Yii::$app->access->can('APB-write')],
    ]) ?>
    
    <?php ActiveForm::end(); ?>

    <?php $output = GridView::widget([
        'id' => 'primary-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
        'layout' => '{items}',
        'columns' => [
            
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['class' => 'text-center', 'style' => 'width:25px'],
                'contentOptions' => ['class' => 'text-center'],
            ],
            'NOSIRI',
            [
                'attribute' => 'NOLESEN',
                'value' => 'pemilik0.NOLESEN',
            ],
            // [
            //     'attribute' => 'NAMAPREMIS',
            //     'value' => 'pemilik0.NAMAPREMIS',
            // ],
            [
                'label' => 'Nama Syarikat',
                'attribute' => 'NAMASYARIKAT',
                'value' => function ($model){
                    return (!empty($model->pemilik0->NAMASYARIKAT))? $model->pemilik0->NAMASYARIKAT: null;
                }
            ],
            [
                'label' => 'Nama Pemilik/Pemohon',
                'attribute' => 'NAMAPEMOHON',
                'value' => function ($model){
                    return (!empty($model->pemilik0->NAMAPEMOHON))? $model->pemilik0->NAMAPEMOHON: null;
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Tindakan',
                'template' => "{views} ",
                'headerOptions' => ['style' => 'width:60px'],
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'views' => function ($url, $model) {
                        return Html::a('<span class="fa fa-eye"></span>', $url, [
                                    'title' => Yii::t('app', 'Paparan'),
                                    //'class'=>'btn btn-primary btn-xs',                                  
                        ]);
                    },
                ],
                // 'buttons' => [
                //     'daftar' => function ($url, $model) {
                //         return Html::a('Daftar', ['/premis/anugerah/create', 'id'=>$model->NOSIRI]);
                //     },
                // ],
                'visibleButtons' => [
                    'views' =>   \Yii::$app->access->can('APB-read'),
                    
                ],
            ],
        ],
    ]); ?>

    <?= \codetitan\widgets\GridNav::widget([
        'dataProvider' => $dataProvider,
        'output' => $output,
        'model' => $searchModel,
        // 'addon' => ['extender' => $extender],
    ]) ?>
</div>
<?php

