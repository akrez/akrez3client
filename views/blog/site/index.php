<?php

use app\components\BlogHelper;
use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$blogSlug = (Yii::$app->blog->attribute('slug') ? Yii::$app->blog->attribute('slug') : '');
$this->registerMetaTag([
    'name' => 'keywords',
    'content' => Helper::normalizeArrayUnorder([Yii::$app->blog->attribute('title'), $blogSlug, Yii::$app->blog->attribute('name')], false, ',') . (isset(Yii::$app->view->params['_categories']) && Yii::$app->view->params['_categories'] ? ',' . implode(',', Yii::$app->view->params['_categories']) : ''),
]);
?>

<div class="v1-default-index">
    <h1 class="mt0" style="display: inline-block;"><?= HtmlPurifier::process(Yii::$app->blog->attribute('title')) ?></h1>
    <h2 class="mt0 h2text" style="display: inline-block;margin-right: 10px;"><?= Yii::$app->blog->attribute('slug') ?></h2>
    <h3 class="mt0" style="text-align: justify;line-height: 1.62em;font-size: 14px;"><?= Yii::$app->blog->attribute('des') ?></h3>
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

<?= $this->render('_products_container') ?>
