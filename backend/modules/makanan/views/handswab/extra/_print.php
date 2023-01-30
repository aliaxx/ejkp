<?php

use yii\helpers\Html;

if (!isset($visible)) {
        // $visible = ['export-csv', 'export-pdf'];
        $visible = ['export-list'];
}

$items = [];
if (in_array('export-list', $visible)) {
    $items['export-list'] = Html::submitButton('Borang Swab Tangan', [
        'id' => 'action_export-pdf', 'name' => 'action[export-list]',
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