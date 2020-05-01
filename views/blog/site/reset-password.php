<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$email = Html::encode(Yii::$app->request->get('email'));
$resetToken = Html::encode(Yii::$app->request->get('reset_token'));
?>

<div class="row">
    <div class="col-sm-5">
        <h1 style="margin-bottom: 20px;"><?= Yii::t('app', 'Reset Password') ?></h1>
        <?php
        $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => '<div class="input-group">{label}{input}</div>{error}',
                        'labelOptions' => [
                            'class' => 'input-group-addon',
                        ],
                    ]
        ]);
        ?>
        <?= $form->field($model, 'email')->textInput($email ? ['value' => $email, 'readonly' => true] : []) ?>
        <?= $form->field($model, 'reset_token')->textInput($resetToken ? ['value' => $resetToken, 'readonly' => true] : []) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" name="login-button" style="float: right;"> <?= Yii::t('app', 'Reset Password') ?> </button>
        </div>
        
        
        
        <?php ActiveForm::end(); ?>
    </div>
</div>
