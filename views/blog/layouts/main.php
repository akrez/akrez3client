<?php

use app\assets\BlogAsset;
use app\components\Alert;
use app\components\BlogHelper;
use app\components\Helper;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\Spaceless;

BlogAsset::register($this);

$this->registerMetaTag([
    'name' => 'keywords',
    'content' => BlogHelper::getMetaKeyword(),
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
                <div class="col-sm-3"><?= $this->render('mainmenu'); ?></div>
                <div class="col-sm-9"><?= Alert::widget() ?><?= $content ?></div>
            </div>
        </div>

        <footer class="footer pb20 pt20" style="background-color: #f8f8f8">
            <div class="container">
                <div class="row ">
                    <div class="col-sm-3 text-center">
                        <h3 class="mt0"><?= HtmlPurifier::process(Yii::$app->blog->attribute('title')) ?></h3>
                        <?php
                        $parts = [];
                        if (Yii::$app->blog->attribute('phone')) {
                            $phone = Html::encode(Yii::$app->blog->attribute('phone'));
                            $parts[] = '<a dir="ltr" href="tel:' . $phone . '">' . $phone . '</a>';
                        }
                        if (Yii::$app->blog->attribute('mobile')) {
                            $mobile = Html::encode(Yii::$app->blog->attribute('mobile'));
                            $parts[] = '<a dir="ltr" href="tel:' . $mobile . '">' . $mobile . '</a>';
                        }
                        echo implode(' - ', $parts);
                        ?>
                    </div>
                    <div class="col-sm-6 text-center">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php
                                $parts = [];
                                if (Yii::$app->blog->attribute('address')) {
                                    $parts[] = '<p>' . Html::encode(Yii::$app->blog->attribute('address')) . '</p>';
                                }
                                if (Yii::$app->blog->attribute('email')) {
                                    $email = Html::encode(Yii::$app->blog->attribute('email'));
                                    $parts[] = '<p><a href="mailto:' . $email . '">' . $email . '</a></p>';
                                }
                                echo implode('', $parts);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <?php
                            if (Yii::$app->blog->attribute('facebook')) {
                                $url = HtmlPurifier::process('https://www.facebook.com/' . Yii::$app->blog->attribute('facebook'));
                                $logo = Html::img(Yii::getAlias('@web/cdn/image/social/facebook.svg'), ['style' => 'margin: auto;', 'class' => 'img-responsive img-rounded', 'alt' => $url]);
                                echo '<div class="col-xs-3 pull-left">' . Html::a($logo, $url, ['style' => 'text-align: center;']) . '</div>';
                            }
                            ?>
                            <?php
                            if (Yii::$app->blog->attribute('twitter')) {
                                $url = HtmlPurifier::process('https://twitter.com/' . Yii::$app->blog->attribute('twitter'));
                                $logo = Html::img(Yii::getAlias('@web/cdn/image/social/twitter.svg'), ['style' => 'margin: auto;', 'class' => 'img-responsive img-rounded', 'alt' => $url]);
                                echo '<div class="col-xs-3 pull-left">' . Html::a($logo, $url, ['style' => 'text-align: center;']) . '</div>';
                            }
                            ?>
                            <?php
                            if (Yii::$app->blog->attribute('instagram')) {
                                $url = HtmlPurifier::process('https://www.instagram.com/' . Yii::$app->blog->attribute('instagram'));
                                $logo = Html::img(Yii::getAlias('@web/cdn/image/social/instagram.svg'), ['style' => 'margin: auto;', 'class' => 'img-responsive img-rounded', 'alt' => $url]);
                                echo '<div class="col-xs-3 pull-left">' . Html::a($logo, $url, ['style' => 'text-align: center;']) . '</div>';
                            }
                            ?>
                            <?php
                            if (Yii::$app->blog->attribute('telegram')) {
                                $url = HtmlPurifier::process('https://telegram.me/' . Yii::$app->blog->attribute('telegram'));
                                $logo = Html::img(Yii::getAlias('@web/cdn/image/social/telegram.svg'), ['style' => 'margin: auto;', 'class' => 'img-responsive img-rounded', 'alt' => $url]);
                                echo '<div class="col-xs-3 pull-left">' . Html::a($logo, $url, ['style' => 'text-align: center;']) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>

</html>
<?php if (YII_ENV != 'dev') Spaceless::end(); ?>
<?php $this->endPage() ?> 