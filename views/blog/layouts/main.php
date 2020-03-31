<?php

use app\assets\BlogAsset;
use app\components\BlogHelper;
use app\components\Alert;
use yii\helpers\Html;
use yii\widgets\Spaceless;

BlogAsset::register($this);
$blogLogo = BlogHelper::getImage('logo', '32', Yii::$app->blog->attribute('logo'));
$titleParts = array_filter(array_unique(array_map("trim", [Yii::$app->blog->attribute('title'), Yii::$app->blog->attribute('slug'), $this->title])));
?>
<?php $this->beginPage() ?>
<?php if (YII_ENV != 'dev') Spaceless::begin(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Yii::$app->view->registerLinkTag(['rel' => 'icon', 'href' => $blogLogo]) ?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(implode(' | ', $titleParts)) ?></title>

        <?php foreach (["apple-touch-icon", "icon",] as $relsValue) : ?>
            <?php foreach (Yii::$app->params['manifestIconSizes'] as $widthsValue) : ?>
                <link sizes="<?= $widthsValue . 'x' . $widthsValue ?>" href="<?= BlogHelper::getImage('logo', $widthsValue, Yii::$app->blog->attribute('logo')) ?>" rel="<?= $relsValue ?>">
            <?php endforeach; ?>
        <?php endforeach; ?>

        <meta name="msapplication-TileImage" content="<?= BlogHelper::getImage('logo', 144, Yii::$app->blog->attribute('logo')) ?>">
        <meta name="msapplication-TileColor" content="#ffffff">

        <meta name="theme-color" content="<?= Yii::$app->params['manifestThemeColor'] ?>">
        <meta name="msapplication-navbutton-color" content="<?= Yii::$app->params['manifestThemeColor'] ?>">
        <meta name="apple-mobile-web-app-status-bar-style" content="<?= Yii::$app->params['manifestThemeColor'] ?>">

        <link rel="manifest" href="<?= BlogHelper::url('/manifest.json') ?>">

        <?php $this->head() ?>
    </head>

    <body class="pt20 pb20">
        <?php $this->beginBody() ?>
        <div class="container">
            <?= $this->render('navbar'); ?>
            <div class="row">
                <?php if ($this->context->id == 'site' && in_array($this->context->action->id, ['signin', 'signup', 'reset-password-request', 'reset-password', 'error',])): ?>
                    <div class="col-sm-12"><?= Alert::widget() ?><?= $content ?></div>
                <?php else: ?>
                    <div class="col-sm-3"><?= $this->render('mainmenu'); ?></div>
                    <div class="col-sm-9"><?= Alert::widget() ?><?= $content ?></div>
                <?php endif; ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>

</html>
<?php if (YII_ENV != 'dev') Spaceless::end(); ?>
<?php $this->endPage() ?> 