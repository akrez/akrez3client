<?php

use blog\components\Api;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$dp = Api::getDp();
?>

<div class="v1-default-index">
    <h1 class="mt0"> <?= HtmlPurifier::process(Api::getBlogAttribute('title')) ?> <small> <?= Api::getBlogAttribute('slug') ?> </small> </h1>
    <p style="text-align: justify;"><?= Api::getBlogAttribute('des') ?></p>
</div>

<div class="row pt20 pb20">
    <?= Html::beginForm(Api::url('site', 'index'), 'GET'); ?>
    <div class="col-sm-5">
        <div class="input-group">

            <?= Html::textInput('Search[title][0][value]', (isset($dp['search']['title'][0]['value']) ? $dp['search']['title'][0]['value'] : null), ['class' => 'form-control']); ?>
            <?= Html::hiddenInput('Search[title][0][operation]', 'LIKE'); ?>
            <span class="input-group-btn">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['style' => 'height: 34px;', 'class' => 'btn btn-default btn-block']); ?>
            </span>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>

<?= $this->render('_products_container', ['dp' => $dp]) ?>
