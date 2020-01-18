<?php

use blog\components\Api;
use common\models\BasketItem;
use common\models\Invoice;
use common\models\Province;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$dp = Api::getDp();

$this->title = Yii::t('app', 'Basket');

$models = [];
foreach ($dp['baskets'] as $basket) {
    $package = $dp['packages'][$basket['package_id']];
    $product = $dp['products'][$package['product_id']];
    $model = new BasketItem();
    $model->load($package, '');
    $model->image = $product['image'];
    $model->title = $product['title'];
    $model->cnt = $basket['cnt'];
    $models[] = $model;
}
?>

<div class="row">
    <div class="col-sm-12 pb20">
        <h1 class="mt0"><?= $this->title ?></h1>
    </div>
</div>

<?php if (empty($models)): ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= Yii::t('yii', 'No results found.'); ?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12 pb20">
            <?= $this->render('_basket_table', ['models' => $models, 'editable' => true]) ?>
        </div>
    </div>
    <?php
    $model = new Invoice();
    if ($dp['invoice'] !== null) {
        $model->setAttributes($dp['invoice']);
        $model->addErrors($dp['errors']);
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
                    'data' => Province::getList(),
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
