<?php

use backend\modules\peniaga\utilities\OptionHandler;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

use common\utilities\DateTimeHelper;

/**
 * @var View $this
 * @var DatakesApp $model
 */


$this->title = 'Gerai/Petak';
$this->params['breadcrumbs'] = [
    'Peniaga Kecil & Penjaja',
    ['label' => 'Kawalan Perniagaan', 'url' => ['index']],
    $model->NOSIRI,
    ['label' => $this->title, 'url' => ['kawalan-perniagaan/gerai', 'nosiri' => $model->NOSIRI]],
    'Paparan Maklumat'
];
?>

<div>
    <div class="action-buttons">
        <?= Html::a('<i class="glyphicon glyphicon-chevron-left"></i> Kembali', ['gerai', 'nosiri' => $model->NOSIRI], [
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
            'ID',
            [
                'attribute' => 'NOPETAK',
                'value' => $model->pemilikSewa0->NOPETAK,
            ],
            [
                'format' => 'date',
                'attribute' => 'TRKHLAWATAN_GERAI',
            ],
            [
                'attribute' => 'NAMAPEMOHON',
                'value' => $model->pemilikSewa0->NAMAPEMOHON,
            ],
            [
                'attribute' => 'NOSEWA',
                'value' => $model->pemilikSewa0->NOSEWA,
            ],
            [
                'attribute' => 'NOLESEN',
                'value' => $model->pemilikSewa0->NOLESEN,
            ],
            [
                'label' => 'Alamat',
                'attribute' => 'ALAMAT1',
                'value' => $model->pemilikSewa0->ALAMAT1.', '.$model->pemilikSewa0->ALAMAT2.', '.$model->pemilikSewa0->ALAMAT3,
            ],
            [
                'attribute' => 'POSKOD',
                'value' => $model->pemilikSewa0->POSKOD,
            ],
            [
                'label' => 'Jenis Sewa',
                'attribute' => 'JENISSEWA',
                'value' => $model->pemilikSewa0->JENISSEWA,
            ],
            [
                'attribute' => 'JENIS_JUALAN',
                'value' => $model->pemilikSewa0->JENIS_JUALAN,
            ],
            'NORUJUKAN',
            [
                'attribute' => 'STATUSPEMANTAUAN',
                'value' => function($model) {
                    return OptionHandler::resolve('status-pemantauan', $model->STATUSPEMANTAUAN);
                }
            ],
            [
                'attribute' => 'TINDAKANPENGUATKUASA',
                'value' => function($model) {
                    return OptionHandler::resolve('tindakan-penguatkuasa', $model->TINDAKANPENGUATKUASA);
                }
            ],
            [
                'attribute' => 'STATUSGERAI',
                'value' => function($model) {
                    return OptionHandler::resolve('status-gerai', $model->STATUSGERAI);
                }
            ],
            [
                'attribute' => 'PRGKP_GREASE',
                'value' => function($model) {
                    return OptionHandler::resolve('perangkap-grease', $model->PRGKP_GREASE);
                }
            ],
            'CATATAN',
            [
                'label' => 'Pengguna Akhir',
                'attribute' => 'PGNAKHIR',
                'value' => function ($model) {
                    return ($pengguna = \common\models\Pengguna::findOne(['ID' => $model->PGNAKHIR]))? $pengguna->NAMA : null;
                },
            ],
            [
                'label' => 'Tarikh Kemaskini',
                'format' => 'datetime',
                'attribute' => 'TRKHAKHIR',
            ],
        ],
    ]) ?>
</div>