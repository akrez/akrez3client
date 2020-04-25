<?php

use app\components\BlogHelper;
use yii\helpers\HtmlPurifier;
use yii\widgets\Breadcrumbs;

?>

<?=
Breadcrumbs::widget([
    'homeLink' => [
        'label' => Yii::t('yii', 'Home'),
        'url' => BlogHelper::blogFirstPageUrl(),
    ],
    'links' => [
        ['label' => Yii::$app->view->params['_categories'][Yii::$app->view->params['categoryId']]],
    ],
]);
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= HtmlPurifier::process(Yii::$app->view->params['_categories'][Yii::$app->view->params['categoryId']]) ?></h1>
    </div>
</div>

<?= $this->render('_products_container') ?>