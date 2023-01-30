<?php

use yii\helpers\Html;

//this page use for print kompaun section in penguatkuasaan PTP - NOR10012023

if (!isset($visible)) {
        // $visible = ['export-csv', 'export-pdf'];
        $visible = ['export-kompaun'];
}

$items = [];
if (in_array('export-kompaun', $visible)) {
    $items['export-kompaun'] = Html::submitButton('Borang PPA - 2', [
        'id' => 'action_export-pdf', 'name' => 'action[export-kompaun]',
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