<?php

use backend\modules\makanan\models\SampelHandswab;
use backend\modules\makanan\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use backend\modules\makanan\models\BarangSitaan;


/**
 * @var View $this
 * @var BarangSitaan $model
 */

$this->title = 'Barang Sitaan';
$this->params['breadcrumbs'] = [
'Mutu Makanan',
['label' => 'Sitaan', 'url' => ['index']],
$model->NOSIRI,
['label' => $this->title, 'url' => ['/makanan/sitaan/sitaan', 'nosiri' => $model->NOSIRI]],
'Paparan Maklumat'
];

$listImg = [];

if ($files = $model->getAttachments()) {
    foreach ($files as $key => $file) {
        $listImg[] = Html::img(Yii::getAlias('@web/' . BarangSitaan::IMAGE_PATH . '/' . basename($file)), [
            'height' => '300',
            'title' => 'Klik untuk besarkan gambar',
            'style' => 'margin:0 15px 20px 0;cursor:pointer',
            'onclick' => 'enlargeImage(this)',
        ]) . '<br>' .
            Html::label(basename($file), ['class' => 'label',])
            . '<br>';
    }
}

?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['sitaan', 'nosiri' => $model->NOSIRI], [
            'id' => 'action_back', 'class' => 'btn btn-danger', 'name' => 'action[back]', 'value' => 1,
            'title' => 'Kembali', 'aria-label' => 'Kembali', 'data-pjax' => 0,
        ]) ?>
    </div>

    <?= $this->render('_tab', [
        'model' => $model,
    ]) ?>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'NOSIRI',
            'JENISMAKANAN',
            'PENGENALAN',
            'KUANTITI',
            'HARGA',
            'KESALAHAN',
            'NAMAPEMBUAT',
            'ALAMATPEMBUAT',
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
