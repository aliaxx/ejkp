<?php

use yii\helpers\Html;

if (!isset($visible)) {
        $visible = ['export-csv', 'export-pdf'];
}

$items = [];
if (in_array('export-csv', $visible)) {
    $items['export-csv'] = Html::submitButton('Cetak CSV', [
        'id' => 'action_export-csv', 'name' => 'action[export-csv]',
        'class' => 'btn-dropdown', 'value' => 1, 'data-pjax' => 0,
    ]);
}

if (in_array('export-pdf', $visible)) {
    $items['export-pdf'] = Html::submitButton('Cetak PDF', [
        'id' => 'action_export-pdf', 'name' => 'action[export-pdf]',
        'class' => 'btn-dropdown' , 'value' => 1, 'data-pjax' => 0, 
    ]);
}
?>  

<?php if ($items): ?>
    <?= \yii\bootstrap\ButtonDropdown::widget([
        'encodeLabel' => false,
        'label' => '<i class="glyphicon glyphicon-print"></i> Cetak',
        'dropdown' => [
            'items' => $items,
        ],
        'dropdownClass' => '\common\widgets\Dropdown',
        'options' => ['class' => ['btn btn-primary'], 'title' => 'Cetak', 'aria-label' => 'Cetak'],
    ]) ?>
<?php endif; ?>