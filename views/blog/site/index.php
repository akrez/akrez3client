<?php

use app\components\BlogHelper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>

<div class="v1-default-index">
    <h1 class="mt0"> <?= HtmlPurifier::process(Yii::$app->blog->attribute('title')) ?> <small> <?= Yii::$app->blog->attribute('slug') ?> </small> </h1>
    <p style="text-align: justify;"><?= Yii::$app->blog->attribute('des') ?></p>
</div>

<div class="row pt20 pb20">
    <?= Html::beginForm(BlogHelper::url('site/index'), 'GET'); ?>
    <div class="col-sm-5">
        <div class="input-group">

            <?= Html::textInput('Search[title][0][value]', (isset(Yii::$app->view->params['search']['title'][0]['value']) ? Yii::$app->view->params['search']['title'][0]['value'] : null), ['class' => 'form-control']); ?>
            <?= Html::hiddenInput('Search[title][0][operation]', 'LIKE'); ?>
            <span class="input-group-btn">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['style' => 'height: 34px;', 'class' => 'btn btn-default btn-block']); ?>
            </span>
        </div>
    </div>
    <?= Html::endForm(); ?>
</div>

<?= $this->render('_products_container')  ?>
