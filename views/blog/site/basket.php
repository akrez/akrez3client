<?php

use app\components\BlogHelper;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Basket');
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= $this->title ?></h1>
    </div>
</div>

<?php if (empty(Yii::$app->view->params['baskets'])): ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= Yii::t('yii', 'No results found.'); ?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= $this->render('_basket_table') ?>
        </div>
    </div>
    <?php
    $model = new app\models\Invoice();
    if (Yii::$app->view->params['invoice'] !== null) {
        $model->setAttributes(Yii::$app->view->params['invoice']);
        $model->addErrors(Yii::$app->view->params['errors']);
    }
    $form = ActiveForm::begin([
                'method' => 'get',
                'fieldConfig' => [
                    'template' => '<div class="input-group">{label}{input}</div>{error}',
                    'labelOptions' => ['class' => 'input-group-addon'],
                    'options' => ['class' => 'col-sm-4',]
                ],
    ]);
    ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <div class="row">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="row">
                <?php
                echo $form->field($model, 'province', ['options' => ['class' => 'col-sm-3'], 'template' => "{input}\n{hint}\n{error}"])->widget(Select2::classname(), [
                    'data' => BlogHelper::getConstant('province'),
                    'hideSearch' => false,
                    'options' => [
                        'placeholder' => $model->getAttributeLabel('province'),
                        'id' => Html::getInputId($model, 'province') . '-' . $model->name,
                        'dir' => 'rtl',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <?= $form->field($model, 'address', ['options' => ['class' => 'col-sm-9']])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="row">
                <?= $form->field($model, 'des', ['options' => ['class' => 'col-sm-12']])->textInput(['maxlength' => true]) ?>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Submit invoice'), ['class' => 'btn btn-success btn-block']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
<?php endif; ?>
