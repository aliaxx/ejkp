<?php

// use backend\modules\makanan\models\SampelHandswab;
use backend\modules\makanan\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use backend\modules\makanan\models\SampelMakanan;

/**
 * @var View $this
 * @var SampelMakanan $model
 */


$this->title = 'Sampel Makanan';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Sampel Makanan', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/sampel/sampel', 'nosiri' => $model->NOSIRI]],
$model->NOSAMPEL,
'Paparan Maklumat'
];


$listImg = [];
if ($files = $model->getAttachments()) {
    foreach ($files as $key => $file) {
        $listImg[] = Html::img(Yii::getAlias('@web/' . SampelMakanan::IMAGE_PATH . '/' . basename($file)), [
            'height' => '300',
            'title' => 'Klik untuk besarkan gambar',
            'style' => 'margin:0 15px 20px 0;cursor:pointer',
            'onclick' => 'enlargeImage(this)',
        ]) . '<br>' .
            Html::label(basename($file), ['class' => 'label',])
            . '<br>';
    }
}

// var_dump($listImg);
// exit();

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['sampel', 'nosiri' => $model->NOSIRI], [ //sampel is action -NOR22092022
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>
    <br><br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOSIRI',
            'NOSAMPEL',
            'TRKHSAMPEL',
            'JENIS_SAMPEL',
            [
                'attribute' => 'ID_JENISANALISIS1',
                'value' => $model->jenisAnalisis1->PRGN, 
            ],
            [
                'attribute' => 'ID_JENISANALISIS2',
                // 'value' => $model->jenisAnalisis2->PRGN, 
                'value' => function ($model) {
                    // return $model->dun->PRGNDUN;
                    return isset($model->jenisAnalisis2->PRGN)?$model->jenisAnalisis2->PRGN:null;                   
                },
            ],
            [
                'attribute' => 'ID_JENISANALISIS3',
                // 'value' => $model->jenisAnalisis3->PRGN, 
                'value' => function ($model) {
                    // return $model->dun->PRGNDUN;
                    return isset($model->jenisAnalisis3->PRGN)?$model->jenisAnalisis3->PRGN:null;                   
                },
            ],
            'JENAMA',
            'HARGA',
            'PEMBEKAL',
            'CATATAN',
            [
                'attribute' => 'PGNDAFTAR',
                'value' => $model->createdByUser->NAMA,
            ],
            'TRKHDAFTAR:datetime',
            [
                'attribute' => 'PGNAKHIR',
                'value' => $model->createdByUser->NAMA,
            ],
            'TRKHAKHIR:datetime',
            [
                'attribute' => 'Gambar',
                'format' => 'raw',
                'value' => function ($model) use ($listImg) {
                    return implode($listImg);
                }
            ],
        ],
    ]) ?>
</div>

<div id="enlargeImageModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Besarkan Gambar</h4>
            </div>
            <div id="enlargeImageModalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>

    </div>
</div>


<?php
$this->registerJs("
function enlargeImage(item) {
    $('#enlargeImageModalBody').html('<img src=\"' + item.src + '\" width=\"100%\" />');
    $('#enlargeImageModal').modal();
}
", View::POS_END);
