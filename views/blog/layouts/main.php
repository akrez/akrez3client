<?php

use app\assets\BlogAsset;
use app\components\Alert;
use app\components\BlogHelper;
use app\components\Helper;
use yii\helpers\Html;
use yii\widgets\Spaceless;

BlogAsset::register($this);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => BlogHelper::getMetaKeyword(isset(Yii::$app->view->params['_categories']) ? Yii::$app->view->params['_categories'] : [], isset(Yii::$app->view->params['categoryId']) ? Yii::$app->view->params['categoryId'] : null ),
]);

$blogSlug = (Yii::$app->blog->attribute('slug') ? Yii::$app->blog->attribute('slug') : '');
$this->title = Helper::normalizeArrayUnorder([$this->title, Yii::$app->blog->attribute('title'), $blogSlug], false, ' | ');
$this->registerMetaTag([
    'name' => 'description',
    'content' => (Yii::$app->blog->attribute('des') ? Yii::$app->blog->attribute('des') : Helper::normalizeArray([Yii::$app->blog->attribute('title'), $blogSlug, Yii::$app->blog->attribute('name')], false, ' - ')),
]);
?>
<?php $this->beginPage() ?>
<?php if (YII_ENV != 'dev') Spaceless::begin(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?=
        Yii::$app->view->registerLinkTag([
            'rel' => 'icon',
            'href' => BlogHelper::getImage('logo', '32_32__1', Yii::$app->blog->attribute('logo')),
        ])
        ?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <?php foreach (["apple-touch-icon", "icon",] as $relsValue) : ?>
            <?php foreach (Yii::$app->params['manifestIconSizes'] as $widthsValue) : ?>
                <link sizes="<?= $widthsValue . 'x' . $widthsValue ?>" href="<?= BlogHelper::getImage('logo', $widthsValue . "_" . $widthsValue . "__1", Yii::$app->blog->attribute('logo')) ?>" rel="<?= $relsValue ?>">
            <?php endforeach; ?>
        <?php endforeach; ?>

        <meta name="msapplication-TileImage" content="<?= BlogHelper::getImage('logo', "144_144__1", Yii::$app->blog->attribute('logo')) ?>">
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