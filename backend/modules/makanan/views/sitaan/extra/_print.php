<?php

use yii\helpers\Html;

if (!isset($visible)) {
        // $visible = ['export-csv', 'export-pdf'];
        $visible = ['export-notis', 'export-persetujuan', 'export-senarai'];
}

$items = [];
if (in_array('export-notis', $visible)) {
    $items['export-notis'] = Html::submitButton('Notis Makanan Yang Dilak/Sita', [
        'id' => 'action_export-pdf', 'name' => 'action[export-notis]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}
if (in_array('export-persetujuan', $visible)) {
    $items['export-persetujuan'] = Html::submitButton('Persetujuan Bagi Pemusnahan Dan Pelupusan Makanan', [
        'id' => 'action_export-pdf', 'name' => 'action[export-persetujuan]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}
if (in_array('export-senarai', $visible)) {
    $items['export-senarai'] = Html::submitButton('Senarai Makanan Yang Di Lak/Sita', [
        'id' => 'action_export-pdf', 'name' => 'action[export-senarai]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}

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