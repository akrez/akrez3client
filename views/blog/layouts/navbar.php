<?php

use blog\components\Api;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

$dp = Api::getDp();

NavBar::begin([
    'brandLabel' => Html::tag('span', Api::getBlogAttribute('title'), ['style' => 'margin: 0px;font-size: 18px;']),
    'brandUrl' => Api::blogFirstPageUrl(),
    'renderInnerContainer' => false,
    'options' => [
        'class' => 'navbar navbar-default',
    ],
]);

if ($categories = Api::getDpAttribute('categories')) {
    $menuItems = [];
    foreach ($categories as $categoryId => $category) {
        $menuItems[] = ['label' => $category, 'url' => Api::url('site', 'category', ['id' => $categoryId])];
    }
    echo Nav::widget([
        'items' => [
            [
                'label' => Yii::t('app', 'Products Categories'),
                'items' => $menuItems,
            ],
        ],
        'options' => ['class' => 'navbar-nav navbar-right'],
    ]);
}
?>

<?php
if (Yii::$app->user->isGuest) {
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            ['label' => Yii::t('app', 'Signup'), 'url' => Api::url('site', 'signup')],
            ['label' => Yii::t('app', 'Signin'), 'url' => Api::url('site', 'signin')],
        ],
    ]);
} else {
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            [
                'label' => strtoupper(Yii::$app->user->getIdentity()->email),
                'items' => [
                    ['label' => Yii::t('app', 'Basket'), 'url' => Api::url('site', 'basket')],
                    ['label' => Yii::t('app', 'Invoice'), 'url' => Api::url('site', 'invoice')],
                    ['label' => Yii::t('app', 'Signout'), 'url' => Api::url('site', 'signout')],
                ],
            ],
        ],
    ]);
}
?>

<?= Html::beginForm(Api::blogFirstPageUrl(), 'GET', ['class' => 'navbar-form navbar-left']); ?>
<div class="input-group">
    <div class="form-group">
        <?= Html::textInput('Search[title][0][value]', null, ['class' => 'form-control']) ?>
        <?= Html::hiddenInput('Search[title][0][operation]', 'LIKE') ?>
    </div>
    <span class="input-group-btn">
        <?= Html::submitButton('<span class="glyphicon glyphicon-search" aria-hidden="true"></span>', ['style' => 'height: 34px;', 'class' => 'btn btn-default']); ?>
    </span>
</div>
<?= Html::endForm(); ?>

<?php NavBar::end(); ?>