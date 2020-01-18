<?php
use blog\assets\BlogAsset;
use blog\components\Api;
use blog\widgets\Alert;
use yii\helpers\Html;

BlogAsset::register($this);

$dp = Api::getDp();
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Yii::$app->view->registerLinkTag(['rel' => 'icon', 'href' => Api::blogLogo('32')]) ?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title ? $this->title : Api::getBlogAttribute('title')) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="pt20 pb20">
        <?php $this->beginBody() ?>
        <div class="container">
            <?= $this->render('navbar'); ?>
            <div class="row">
                <?php if ($this->context->id == 'site' && in_array($this->context->action->id, ['signin', 'signup', 'reset-password-request', 'reset-password'])): ?>
                    <div class="col-sm-12">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                <?php else: ?>
                    <div class="col-sm-3">
                        <?= $this->render('mainmenu'); ?>
                    </div>
                    <div class="col-sm-9">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?> 