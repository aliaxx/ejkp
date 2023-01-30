<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\Peranan */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Cari';
$this->params['breadcrumbs'] = [
    'Pengguna',
    ['label' => 'Pengurusan', 'url' => ['index']],
    'Cari',
];
?>
<?php 
    $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::toRoute("user/cari"),
        'id' => 'login-form-inline', 
        'type' => ActiveForm::TYPE_INLINE,
        'tooltipStyleFeedback' => true, // shows tooltip styled validation error feedback
        'fieldConfig' => ['options' => ['class'=>'form-group mt-2 mr-2']], // spacing field groups
        'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_MEDIUM],
        'formConfig' => ['showErrors' => true],
        'options' => ['style' => 'align-items: flex-start'] // set style for proper tooltips error display
    ]); 
?>

<?php
$data = [
    "nama" => "NAMA",
    "nokp" => "NOKP"
];
?>

   <?= $form->field($model, 'jenis_carian')->widget(Select2::className(),[
    'data' => $data,
    'options' => ['placeholder' => 'Carian'],
    'size' => Select2::MEDIUM,
    'pluginOptions' => [
        'tags' => true,
        'tokenSeparators' => [',', ' '],
        'maximumInputLength' => 10
    ],
    ]); 
    ?>

    <?= $form->field($model, 'nama_pengguna', [
      'addon' => [
          'append' => [
              'content' => Html::submitButton('<i class="glyphicon glyphicon-search"></i> Cari', ['class'=>'btn btn-primary']), 
              'asButton' => true
          ]
      ]
    ])->textInput(['size'=>143]);
    ?>


<?php ActiveForm::end(); ?>
    <hr>
    <?php $form = ActiveForm::begin(); ?>
    <div class="action-buttons">
      <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['index'], [
          'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]',
          'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
      ]) ?>
  </div>
<?php ActiveForm::end(); ?>

<?php

if($dataProvider==NULL){
  
}else{
  Pjax::begin(['id' => 'countries']);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-bordered table-striped table-condensed table-hover table-responsive'],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'ID', 
                    'value' => 'USERID',
                ],    
                [
                    'attribute' => 'ID Staf', 
                    'value' => 'CUSTOMERID',
                ],
                [
                    'attribute' => 'Nama Pengguna', 
                    'value' => 'USERNAME',
                ],
                [
                    "attribute" => 'Nama',
                    'value' => 'NAME',
                ],        
                [
                'attribute' => 'NO. Kad Pengenalan', 
                'value' => 'NIRC',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{cetak}',
                    'headerOptions' => ['style' => 'width:60px'],
                    'contentOptions' => ['class' => 'text-center'],
                    'buttons' => [
                        'cetak' => function ($url, $dataProvider) {
                            $alldata = json_encode($dataProvider);
                            $id = $dataProvider['USERID'];
                            // $result = Yii::$app->db->createCommand("SELECT * FROM TBPENGGUNA WHERE USERNAME = $id")->execute();
                            $result = (new \yii\db\Query())
                            ->select('*')
                            ->from('C##EJKP.TBPENGGUNA')
                            ->where(['ID' => $id])
                            ->all();    

                        if(empty($result)){
                            return Html::a('<i class="glyphicon glyphicon-plus-sign"></i> Tambah', ['/user/add', 'id'=>$alldata], ['class' => 'label label-primary']);
                        }else{
                        return Html::a('<i class="glyphicon glyphicon-refresh"></i> Kemaskini', ['/user/kemaskini', 'id'=>$alldata], ['class' => 'label label-warning']);
                        }
                        },
                    ],
                    'visibleButtons' => [
                        'cetak' =>   \Yii::$app->access->can('pengguna-read'),
                    ],
                ],
            ]
    ]);
       Pjax::end();
}
?>


            
            
            