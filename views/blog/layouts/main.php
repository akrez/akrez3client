<?php

use app\assets\BlogAsset;
use app\components\BlogHelper;
use app\components\Alert;
use yii\helpers\Html;

BlogAsset::register($this);
$blogLogo = BlogHelper::getImage('logo', '32', Yii::$app->blog->attribute('logo'));
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Yii::$app->view->registerLinkTag(['rel' => 'icon', 'href' => $blogLogo]) ?>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title ? $this->title : Yii::$app->blog->attribute('title')) ?></title>
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