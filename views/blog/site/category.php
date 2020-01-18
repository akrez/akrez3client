<?php

use blog\components\Api;
use yii\helpers\HtmlPurifier;
use yii\widgets\Breadcrumbs;

$dp = Api::getDp();
$this->title = Api::getBlogAttribute('title') . ' | ' . $dp['categories'][$dp['categoryId']];
?>

<?=
Breadcrumbs::widget([
    'homeLink' => [
        'label' => Yii::t('yii', 'Home'),
        'url' => Api::blogFirstPageUrl(),
    ],
    'links' => [
        ['label' => $dp['categories'][$dp['categoryId']]],
    ],
]);
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= HtmlPurifier::process($dp['categories'][$dp['categoryId']]) ?></h1>
    </div>
</div>

<?= $this->render('_products_container', ['dp' => $dp]) ?>