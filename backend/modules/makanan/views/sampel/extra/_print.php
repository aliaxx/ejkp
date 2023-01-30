<?php

use yii\helpers\Html;

if (!isset($visible)) {
        // $visible = ['export-csv', 'export-pdf'];
        $visible = ['export-permintaan', 'export-notis', 'export-resit'];
}

$items = [];
if (in_array('export-permintaan', $visible)) {
    $items['export-permintaan'] = Html::submitButton('Permintaan Bagi Analisis Sampel Makanan', [
        'id' => 'action_export-pdf', 'name' => 'action[export-permintaan]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}
if (in_array('export-notis', $visible)) {
    $items['export-notis'] = Html::submitButton('Notis Persampelan Makanan', [
        'id' => 'action_export-pdf', 'name' => 'action[export-notis]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}
if (in_array('export-resit', $visible)) {
    $items['export-resit'] = Html::submitButton('Resit Pembelian', [
        'id' => 'action_export-pdf', 'name' => 'action[export-resit]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}

// if (in_array('export-pdf', $visible)) {
//     $items['export-pdf'] = ['label' => 'Cetak', 'items' => [
//         ['label' => 'Permintaan Bagi Analisis Sampel Makanan', 'url' => ['sampel/print'], 'linkOptions' => array('target' => '_blank')],
//         ['label' => 'Notis Persampelan Makanan', 'url' => ['/document/Notis.pdf'], 'linkOptions' => array('target' => '_blank')], 
//         ['label' => 'Resit Pembelian', 'url' => ['/document/Resit.pdf'], 'linkOptions' => array('target' => '_blank')], 
//     ]];
// }

?>  

<?php if ($items): ?>
    <?= \yii\bootstrap\ButtonDropdown::widget([
        'encodeLabel' => false,
        'label' => '<i class="glyphicon glyphicon-print"></i> Cetak',
        // 'options' => ['class' => 'navbar-nav navbar-right'],
        'dropdown' => [
            'items' => $items,
        ],
        'dropdownClass' => '\common\widgets\Dropdown',
        'options' => ['class' => ['btn btn-primary'], 'title' => 'Cetak', 'aria-label' => 'Cetak'],
    ]) ?>
<?php endif; ?>