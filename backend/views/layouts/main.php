<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

\kartik\icons\Icon::map($this);
\backend\assets\AppAsset::register($this);

if ($this->title) $this->title = Yii::$app->name.' - '.$this->title;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->render('_navbar') ?>

    <?php
        // Remember sidenav state
        $sideNavClass = '';
        if (isset($_COOKIE['sidenav-state']) && !$_COOKIE['sidenav-state']) {
            $sideNavClass = 'sidenav-off';
        }
        
    ?>
    <div id="main-container" class="container <?= $sideNavClass ?>">
    <?php if (!Yii::$app->user->isGuest || (Yii::$app->controller->route != 'site/error')): ?>
        <?= $this->render('_sidenav') ?>
    <?php endif; ?>

        <div class="content">
            <div style="width:100%;padding:15px;padding-top:0;">
                <?= Breadcrumbs::widget([
                    'homeLink' => false,
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>

                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
