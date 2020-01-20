<?php

use yii\helpers\Html;

$this->title = $name;

$blog = Yii::$app->blog->getIdentity();
if ($blog) {
    $this->context->layout = 'main';
} else {
    $this->context->layout = 'blank';
}
?>
<div class="jumbotron">
    <h1><?= Html::encode($exception->statusCode) ?></h1>
    <p><?= nl2br(Html::encode($message)) ?></p>
</div>
